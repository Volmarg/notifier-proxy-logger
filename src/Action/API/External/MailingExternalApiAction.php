<?php

namespace App\Action\API\External;

use App\Attributes\IsApiRoute;
use App\Controller\Application;
use App\Controller\Core\ConfigLoaders;
use App\Controller\Core\Controllers;
use App\DTO\API\BaseApiResponseDto;
use App\DTO\API\Internal\Email\GetEmailStatusResponseDto;
use App\DTO\API\Internal\Email\InsertEmailResponseDto;
use App\DTO\Modules\Mailing\MailDTO;
use App\Entity\Modules\Mailing\Mail;
use App\Entity\Modules\Mailing\MailAttachment;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use TypeError;

#[Route("/api/external", name: "api_external_")]
class MailingExternalApiAction extends AbstractController
{

    /**
     * @var Application $app
     */
    private Application $app;

    /**
     * @var Controllers $controllers
     */
    private Controllers $controllers;

    /**
     * @var ConfigLoaders $configLoaders
     */
    private ConfigLoaders $configLoaders;

    /**
     * @var EntityManagerInterface $entityManager
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var KernelInterface $kernel
     */
    private KernelInterface $kernel;

    /**
     * @param Application            $app
     * @param Controllers            $controllers
     * @param ConfigLoaders          $configLoaders
     * @param EntityManagerInterface $entityManager
     * @param KernelInterface        $kernel
     */
    public function __construct(Application $app, Controllers $controllers, ConfigLoaders $configLoaders, EntityManagerInterface $entityManager, KernelInterface $kernel)
    {
        $this->app           = $app;
        $this->kernel        = $kernel;
        $this->entityManager = $entityManager;
        $this->configLoaders = $configLoaders;
        $this->controllers   = $controllers;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[IsApiRoute]
    #[Route("/mailing/insert-mail", name: "mailing_insert_mail", methods: ["POST"])]
    public function insertMail(Request $request): JsonResponse
    {
        try{
            $this->entityManager->beginTransaction();
            $this->app->getLoggerService()->getLogger()->info("API method has been called: ", [
                __CLASS__ . "::" . __METHOD__,
            ]);

            $responseDto  = new InsertEmailResponseDto();
            $responseDto->prefillBaseFieldsForSuccessResponse();

            $json = $request->getContent();

            json_decode($json, true);
            if( JSON_ERROR_NONE !== json_last_error() ){
                $this->app->getLoggerService()->getLogger()->info("Provided json has invalid syntax", [
                    "json_error" => json_last_error_msg(),
                    "json"       => $json,
                ]);
                $message = $this->app->trans("api.external.general.messages.invalidJsonSyntax");
                $responseDto->prefillBaseFieldsForBadRequestResponse();
                $responseDto->setMessage($message);

                return $responseDto->toJsonResponse();
            }

            $mailDto = MailDTO::fromJson($json);
            $mail    = $this->controllers->getMailingController()->buildMailEntityFromMailDto($mailDto);
            $mail    = $this->controllers->getMailingController()->saveEntity($mail); // must be saved first to obtain the id

            $mailAttachmentsDirectoryRelativePath = $this->configLoaders->getSystemDataConfigLoader()->getRelativeMailAttachmentsFolder() . DIRECTORY_SEPARATOR . $mail->getId();
            $mailAttachmentsDirectoryAbsolutePath = $this->kernel->getProjectDir() . $mailAttachmentsDirectoryRelativePath;
            foreach($mailDto->getAttachments() as $fileName => $fileContent){
                $filePath = $mailAttachmentsDirectoryAbsolutePath. DIRECTORY_SEPARATOR . $fileName;

                $isDirCreated = mkdir($mailAttachmentsDirectoryAbsolutePath, 0777, true);
                if(!$isDirCreated) {
                    throw new Exception("Could not created folder for E-Mail attachments: {$mailAttachmentsDirectoryAbsolutePath}");
                }

                $bytesInserted = file_put_contents($filePath, base64_decode($fileContent));
                if( empty($bytesInserted) ) {
                    throw new Exception("Could not create file for E-Mail: {$filePath}");
                }

                $attachmentEntity = new MailAttachment($mailAttachmentsDirectoryRelativePath, $fileName, (new File($filePath))->getExtension());
                $mail->addAttachment($attachmentEntity);
            }

            if( !empty($mailDto->getAttachments()) ){
                $mail = $this->controllers->getMailingController()->saveEntity($mail); // save attachments
            }

            $message = $this->app->trans("api.external.general.messages.ok");
            $responseDto->setMessage($message);
            $responseDto->setId($mail->getId());

            $this->entityManager->commit();
            $this->app->getLoggerService()->getLogger()->info("Api call finished with success");
            return $responseDto->toJsonResponse();
        }catch(Exception| TypeError $e){
            $this->entityManager->rollback();

            // this means that the code reached the attachments saving logic before it crashed
            if( isset($mailAttachmentsDirectoryAbsolutePath) ){
                rmdir($mailAttachmentsDirectoryAbsolutePath);
            }

            $this->app->getLoggerService()->logThrowable($e, [
                "info" => "Issue occurred while handling external API method for inserting mail"
            ]);
            $message = $this->app->trans('api.external.general.messages.internalServerError');

            $responseDto = BaseApiResponseDto::buildInternalServerErrorResponse();
            $responseDto->setMessage($message);

            return $responseDto->toJsonResponse();
        }
    }

    /**
     * Will return email status {@see Mail::ALL_STATUSES}
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    #[IsApiRoute]
    #[Route("/mailing/get-mail-status/{id}", name: "mailing_get_mail_status", requirements: ["id" => "\d+"], methods: [Request::METHOD_GET])]
    public function getMailStatus(int $id): JsonResponse
    {
        try{
            $this->app->getLoggerService()->getLogger()->info("API method has been called: ", [
                __CLASS__ . "::" . __METHOD__,
            ]);

            $responseDto  = new GetEmailStatusResponseDto();
            $responseDto->prefillBaseFieldsForSuccessResponse();

            $email = $this->controllers->getMailingController()->findOne($id);
            if (empty($email) ){
                $message = $this->app->trans("api.external.general.messages.notFound");
                return GetEmailStatusResponseDto::buildBadRequestErrorResponse($message)->toJsonResponse();
            }

            $message = $this->app->trans("api.external.general.messages.ok");
            $responseDto->setMessage($message);
            $responseDto->setStatus($email->getStatus());

            $this->app->getLoggerService()->getLogger()->info("Api call finished with success");

            return $responseDto->toJsonResponse();
        }catch(Exception | TypeError $e){
            $this->app->getLoggerService()->logThrowable($e, [
                "info" => "Issue occurred while handling external API method for getting E-Mail status"
            ]);
            $message = $this->app->trans('api.external.general.messages.internalServerError');

            $responseDto = GetEmailStatusResponseDto::buildInternalServerErrorResponse();
            $responseDto->setMessage($message);

            return $responseDto->toJsonResponse();
        }
    }

}