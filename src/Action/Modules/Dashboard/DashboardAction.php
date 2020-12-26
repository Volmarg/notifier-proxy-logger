<?php

namespace App\Action\Modules\Dashboard;

use App\Controller\Application;
use App\Controller\Core\Controllers;
use App\DTO\API\BaseApiResponseDto;
use App\DTO\API\Internal\GetLastProcessedEmailsResponseDto;
use App\DTO\Modules\Mailing\MailDTO;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/modules/dashboard", name: "modules_dashboard_")]
class DashboardAction extends AbstractController
{
    const GET_LAST_PROCESSED_EMAILS_DEFAULT_COUNT = 5;

    /**
     * @var Application $app
     */
    private Application $app;

    /**
     * @var Controllers $controllers
     */
    private Controllers $controllers;

    public function __construct(Application $app, Controllers $controllers)
    {
        $this->app         = $app;
        $this->controllers = $controllers;
    }

    /**
     * Will return Last processed E-mails for the dashboard widget `last-processed-emails`
     * @param int $emailsCount
     * @return JsonResponse
     */
    #[Route("/get-last-processed-emails/{emailsCount}", name: "get_last_processed_emails")]
    public function getLastProcessedEmails(int $emailsCount = self::GET_LAST_PROCESSED_EMAILS_DEFAULT_COUNT): JsonResponse
    {
        try{
            $lastProcessedEmails = $this->controllers->getMailingController()->getLastEmails($emailsCount);
            $emailsJsons         = [];

            foreach( $lastProcessedEmails as $mail ){
                $mailDto = new MailDTO();
                $mailDto->setBody($mail->getBody());
                $mailDto->setStatus($mail->getStatus());
                $mailDto->setSubject($mail->getSubject());
                $mailDto->setCreated($mail->getCreated()->format("Y-m-d H:i:s"));

                $emailsJsons[] = $mailDto->toJson();
            }

            $dto = new GetLastProcessedEmailsResponseDto();
            $dto->prefillBaseFieldsForSuccessResponse();
            $dto->setEmailsJsons($emailsJsons);
        }catch(Exception $e){
            $this->app->getLoggerService()->logThrowable($e);

            $message = $this->app->trans('pages.mailing.history.messages.errors.couldNotGetAllSentEmails');

            $baseResponseDto = BaseApiResponseDto::buildInternalServerErrorResponse();
            $baseResponseDto->setMessage($message);

            return $baseResponseDto->toJsonResponse();
        }

        return $dto->toJsonResponse();
    }

}