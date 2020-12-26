<?php

namespace App\Action\API\External;

use App\Controller\Application;
use App\Controller\Core\Controllers;
use App\DTO\API\BaseApiResponseDto;
use App\DTO\Modules\Mailing\MailDTO;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Application $app
     * @param Controllers $controllers
     */
    public function __construct(Application $app, Controllers $controllers)
    {
        $this->app         = $app;
        $this->controllers = $controllers;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route("/mailing/insert-mail", name: "mailing_insert_mail", methods: ["POST"])]
    public function insertMail(Request $request): JsonResponse
    {
        try{
            $baseApiResponseDto  = new BaseApiResponseDto();
            $baseApiResponseDto->prefillBaseFieldsForSuccessResponse();

            $json = $request->getContent();

            json_decode($json, true);
            if( JSON_ERROR_NONE !== json_last_error() ){
                $message = $this->app->trans("api.external.general.messages.invalidJsonSyntax");
                $baseApiResponseDto->prefillBaseFieldsForBadRequestResponse();
                $baseApiResponseDto->setMessage($message);

                return $baseApiResponseDto->toJsonResponse();
            }

            $mailDto = MailDTO::fromJson($json);
            $mail    = $this->controllers->getMailingController()->buildMailEntityFromMailDto($mailDto);

            $this->controllers->getMailingController()->saveEntity($mail);

            return $baseApiResponseDto->toJsonResponse();
        }catch(Exception| TypeError $e){
            $this->app->getLoggerService()->logThrowable($e, [
                "info" => "Issue occurred while handling external API method for inserting mail"
            ]);
            $message = $this->app->trans('api.external.general.messages.internalServerError');

            $responseDto = BaseApiResponseDto::buildInternalServerErrorResponse();
            $responseDto->setMessage($message);

            return $responseDto->toJsonResponse();
        }
    }

}