<?php

namespace App\Action\Modules\Mailing;

use App\Controller\Application;
use App\DTO\API\Internal\BaseInternalApiResponseDto;
use App\Services\Internal\FormService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Notifier\NotifierInterface;
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
            $testMailForm    = $this->application->getForms()->getSendTestMailForm();
            $baseResponseDto = new BaseInternalApiResponseDto();
            $baseResponseDto->prefillBaseFieldsForSuccessResponse();

            $testMailForm = $this->formService->handlePostFormForAxiosCall($testMailForm, $request);
            if( $testMailForm->isSubmitted() && $testMailForm->isValid() ){
                $sendTestMailDto = $testMailForm->getData();
            }

            //todo - send via notifier

            return $baseResponseDto->toJsonResponse();
        }catch(Exception $e){
            $this->application->getLoggerService()->logThrowable($e);
            return BaseInternalApiResponseDto::buildInternalServerErrorResponse()->toJsonResponse();
        }
    }

}