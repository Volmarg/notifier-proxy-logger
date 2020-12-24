<?php

namespace App\Action\Modules\Mailing;

use App\Controller\Application;
use App\DTO\API\Internal\BaseInternalApiResponseDto;
use App\DTO\API\Internal\GetAllEmailsResponseDto;
use App\DTO\Modules\Mailing\MailDTO;
use App\DTO\Modules\Mailing\SendTestMailDTO;
use App\Services\Internal\FormService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/modules/mailing", name: "modules_mailing_")]
class MailingAction extends AbstractController
{
    /**
     * @var Application $application
     */
    private Application $application;

    /**
     * @var NotifierInterface $notifier
     */
    private NotifierInterface $notifier;

    /**
     * @var FormService $formService
     */
    private FormService $formService;

    public function __construct(Application $application, NotifierInterface $notifier, FormService $formService)
    {
        $this->application = $application;
        $this->formService = $formService;
        $this->notifier    = $notifier;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route("/send-test-mail", name: "send_test_mail", methods: ["POST"])]
    public function sendTestMail(Request $request): JsonResponse
    {
        try{
            $testMailForm = $this->application->getForms()->getSendTestMailForm();
            $message      = $this->application->trans('pages.mailing.sendTestMail.messages.success');

            $baseResponseDto = new BaseInternalApiResponseDto();
            $baseResponseDto->prefillBaseFieldsForSuccessResponse();

            $testMailForm = $this->formService->handlePostFormForAxiosCall($testMailForm, $request);
            if( $testMailForm->isSubmitted() && $testMailForm->isValid() ){
                /**
                 * @var SendTestMailDTO $sendTestMailDto
                 */
                $sendTestMailDto = $testMailForm->getData();

                $notification = new Notification();
                $notification->subject($sendTestMailDto->getMessageTitle());
                $notification->content($sendTestMailDto->getMessageBody());

                $notificationRecipient = new Recipient($sendTestMailDto->getReceiver());
                $this->notifier->send($notification, $notificationRecipient);
            }elseif( $testMailForm->isSubmitted() && !$testMailForm->isValid() ){
                $message = $this->application->trans('pages.mailing.sendTestMail.messages.fail');
                $baseResponseDto->prefillBaseFieldsForBadRequestResponse();
            }

            $baseResponseDto->setMessage($message);
            return $baseResponseDto->toJsonResponse();
        }catch(Exception $e){
            $this->application->getLoggerService()->logThrowable($e);

            $message = $this->application->trans('pages.mailing.sendTestMail.messages.fail');

            $baseResponseDto = BaseInternalApiResponseDto::buildInternalServerErrorResponse();
            $baseResponseDto->setMessage($message);

            return $baseResponseDto->toJsonResponse();
        }
    }

    /**
     * Will return all emails
     * @return JsonResponse
     */
    #[Route("/get-all-emails", name: "get_all_emails", methods: ["GET"])]
    public function getAllEmails(): JsonResponse
    {
        try{
            $mails = $this->application->getRepositories()->getMailRepository()->getAllEmails();

            $arrayOfDtosJsons = [];
            foreach($mails as $mail){
                $mailDto = new MailDTO();
                $mailDto->setBody($mail->getBody());
                $mailDto->setSubject($mail->getSubject());
                $mailDto->setCreated($mail->getCreated()->format("Y-m-d H:i:s"));
                $mailDto->setToEmails($mail->getToEmails());
                $mailDto->setStatus($mail->getStatus());

                $arrayOfDtosJsons[] = $mailDto->toJson();
            }

            $responseDto = new GetAllEmailsResponseDto();
            $responseDto->prefillBaseFieldsForSuccessResponse();
            $responseDto->setEmailsJsons($arrayOfDtosJsons);

            return $responseDto->toJsonResponse();
        }catch(Exception $e){
            $this->application->getLoggerService()->logThrowable($e);

            $message = $this->application->trans('pages.mailing.history.messages.errors.couldNotGetAllSentEmails');

            $baseResponseDto = BaseInternalApiResponseDto::buildInternalServerErrorResponse();
            $baseResponseDto->setMessage($message);

            return $baseResponseDto->toJsonResponse();
        }
    }
}