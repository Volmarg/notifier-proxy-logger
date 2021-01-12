<?php

namespace App\Action\Modules\Discord;

use App\Controller\Application;
use App\Controller\Core\Controllers;
use App\DTO\API\BaseApiResponseDto;
use App\DTO\API\External\DiscordWebhookResponseDto;
use App\DTO\API\Internal\GetAllDiscordMessagesResponseDto;
use App\DTO\API\Internal\GetAllDiscordWebhooksResponseDto;
use App\DTO\Modules\Discord\DiscordMessageDTO;
use App\DTO\Modules\Discord\DiscordWebhookDto;
use App\Entity\Modules\Discord\DiscordMessage;
use App\Form\Modules\Discord\SendTestDiscordMessageForm;
use App\Services\External\DiscordService;
use App\Services\Internal\FormService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/modules/discord", name: "modules_discord_")]
class DiscordAction extends AbstractController
{

    const KEY_WEBHOOK_ID = "webhookId";
    const KEY_MESSAGE    = "message";
    const KEY_TITLE      = "title";

    /**
     * @var Application $app
     */
    private Application $app;

    /**
     * @var Controllers $controllers
     */
    private Controllers $controllers;

    /**
     * @var DiscordService $discordService
     */
    private DiscordService $discordService;

    /**
     * @var FormService $formService
     */
    private FormService $formService;

    public function __construct(Application $app, Controllers $controllers, DiscordService $discordService, FormService $formService)
    {
        $this->app            = $app;
        $this->formService    = $formService;
        $this->controllers    = $controllers;
        $this->discordService = $discordService;
    }

    /**
     * This function will handle the request of sending test message to the discord webhook
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route("/send-test-message-discord", name: "send_test_message_discord", methods: ["POST"])]
    public function testSending(Request $request): JsonResponse
    {
        $requestBodyContentJson = $request->getContent();
        $dataArray              = json_decode($requestBodyContentJson, true);

        if( !array_key_exists(self::KEY_MESSAGE, $dataArray) ){
            $message = $this->app->trans('pages.discord.testMessageSending.missingParameterInRequest', [
                "{{parameterName}}" => self::KEY_MESSAGE
            ]);
            $responseDto = DiscordWebhookResponseDto::buildBadRequestErrorResponse();

            $responseDto->setMessage($message);
            return $responseDto->toJsonResponse();
        }

        if( !array_key_exists(self::KEY_WEBHOOK_ID, $dataArray) ){
            $message = $this->app->trans('pages.discord.testMessageSending.missingParameterInRequest', [
                "{{parameterName}}" => self::KEY_WEBHOOK_ID
            ]);
            $responseDto = DiscordWebhookResponseDto::buildBadRequestErrorResponse();

            $responseDto->setMessage($message);
            return $responseDto->toJsonResponse();
        }

        if( !array_key_exists(self::KEY_TITLE, $dataArray) ){
            $message = $this->app->trans('pages.discord.testMessageSending.missingParameterInRequest', [
                "{{parameterName}}" => self::KEY_TITLE
            ]);
            $responseDto = DiscordWebhookResponseDto::buildBadRequestErrorResponse();

            $responseDto->setMessage($message);
            return $responseDto->toJsonResponse();
        }

        $webhookId      = $dataArray[self::KEY_WEBHOOK_ID];
        $webhookMessage = $dataArray[self::KEY_MESSAGE];
        $webhookTitle   = $dataArray[self::KEY_TITLE];

        try{

            $allWebhooks                = $this->controllers->getDiscordWebhookController()->getAll();
            $sendTestMessageDiscordForm = $this->app->getForms()->getSendTestDiscordMessageForm(null, [
                SendTestDiscordMessageForm::FORM_DATA_WEBHOOKS_ENTITIES_ARRAY => $allWebhooks,
            ]);
            $sendTestMessageDiscordForm = $this->formService->handlePostFormForAxiosCall($sendTestMessageDiscordForm, $request);

            if( $sendTestMessageDiscordForm->isSubmitted() && $sendTestMessageDiscordForm->isValid() ){

                $discordWebhook = $this->controllers->getDiscordWebhookController()->getOneById($webhookId);

                if( empty($discordWebhook) ){
                    $message     = $this->app->trans('pages.discord.testMessageSending.fail');
                    $responseDto = DiscordWebhookResponseDto::buildBadRequestErrorResponse();

                    $responseDto->setMessage($message);
                    return $responseDto->toJsonResponse();
                }

                $discordMessage = new DiscordMessage();
                $discordMessage->setMessageContent($webhookMessage);
                $discordMessage->setMessageTitle($webhookTitle);

                $responseDto = $this->discordService->sendDiscordMessage($discordWebhook, $discordMessage);
            }else{

                $this->app->getLoggerService()->getLogger()->critical("Either the form was not submitted or there is an error within the form", [
                    "formErrors" => $sendTestMessageDiscordForm->getErrors(),
                ]);
                $message = $this->app->trans('pages.discord.testMessageSending.fail');
                $responseDto = DiscordWebhookResponseDto::buildBadRequestErrorResponse();
                $responseDto->setMessage($message);;
            }
        }catch(Exception $e) {
            $this->app->getLoggerService()->logThrowable($e);

            $message = $this->app->trans('pages.discord.testMessageSending.fail');

            $baseResponseDto = DiscordWebhookResponseDto::buildInternalServerErrorResponse();
            $baseResponseDto->setMessage($message);

            return $baseResponseDto->toJsonResponse();
        }

        return $responseDto->toJsonResponse();
    }

    /**
     * Handles the frontend (axios) request to add the discord webhook
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route("/add-webhook", name: "add_webhook", methods: ["POST"])]
    public function addWebhook(Request $request): JsonResponse
    {
        try{
            $successMessage = $this->app->trans('pages.discord.addDiscordWebhook.messages.success');

            $baseResponseDto = new BaseApiResponseDto();
            $baseResponseDto->prefillBaseFieldsForSuccessResponse();
            $baseResponseDto->setMessage($successMessage);

            $addDiscordWebhookForm = $this->app->getForms()->getAddDiscordWebhookForm();
            $this->formService->handlePostFormForAxiosCall($addDiscordWebhookForm, $request);

            if( $addDiscordWebhookForm->isSubmitted() && $addDiscordWebhookForm->isValid() ){
                $discordWebhook = $addDiscordWebhookForm->getData();
                $this->controllers->getDiscordWebhookController()->save($discordWebhook);
            }else{
                $failMessage = $this->app->trans('pages.discord.addDiscordWebhook.messages.invalidDataHasBeenProvided');

                $baseResponseDto->prefillBaseFieldsForBadRequestResponse();
                $baseResponseDto->setMessage($failMessage);
            }
        }catch(Exception $e){
            $this->app->getLoggerService()->logThrowable($e);

            $message = $this->app->trans('pages.discord.addDiscordWebhook.messages.fail');

            $baseResponseDto = BaseApiResponseDto::buildInternalServerErrorResponse();
            $baseResponseDto->setMessage($message);

            return $baseResponseDto->toJsonResponse();
        }

        return $baseResponseDto->toJsonResponse();
    }


    /**
     * Will return all webhooks
     *
     * @return JsonResponse
     */
    #[Route("/get-all-webhooks", name: "get_all_webhooks", methods: ["GET"])]
    public function getAllWebhooks(): JsonResponse
    {
        try{
            $message = $this->app->trans('pages.discord.getAllWebhooks.messages.success');

            $discordWebhooks         = $this->controllers->getDiscordWebhookController()->getAll();
            $discordWebhooksDtoJsons = [];

            foreach($discordWebhooks as $discordWebhook){
                $discordWebhookDto = new DiscordWebhookDto();
                $discordWebhookDto->setId($discordWebhook->getId());
                $discordWebhookDto->setDescription($discordWebhook->getDescription());
                $discordWebhookDto->setUsername($discordWebhook->getUsername());
                $discordWebhookDto->setWebhookName($discordWebhook->getWebhookName());;
                $discordWebhookDto->setWebhookUrl($discordWebhook->getWebhookUrl());

                $discordWebhooksDtoJsons[] = $discordWebhookDto;
            }

            $responseDto = new GetAllDiscordWebhooksResponseDto();
            $responseDto->prefillBaseFieldsForSuccessResponse();
            $responseDto->setMessage($message);
            $responseDto->setWebhooksDto($discordWebhooksDtoJsons);

        }catch(Exception $e){
            $this->app->getLoggerService()->logThrowable($e);

            $message = $this->app->trans('pages.discord.getAllWebhooks.messages.fail');

            $baseResponseDto = BaseApiResponseDto::buildInternalServerErrorResponse();
            $baseResponseDto->setMessage($message);

            return $baseResponseDto->toJsonResponse();
        }

        return $responseDto->toJsonResponse();
    }

    /**
     * Will return all DiscordMessage
     * @return JsonResponse
     */
    #[Route("/get-all-discord-messages", name: "get_all_discord_messages", methods: ["GET"])]
    public function getAllDiscordMessages(): JsonResponse
    {
        try{
            $allDiscordMessages = $this->controllers->getDiscordMessageController()->getAllMessages();

            $arrayOfDtosJsons = [];
            foreach($allDiscordMessages as $message){
                $discordMessageDto = new DiscordMessageDTO();
                $discordMessageDto->setMessageContent($message->getMessageContent());
                $discordMessageDto->setMessageTitle($message->getMessageTitle());
                $discordMessageDto->setCreated($message->getCreated()->format("Y-m-d H:i:s"));
                $discordMessageDto->setStatus($message->getStatus());

                $arrayOfDtosJsons[] = $discordMessageDto->toJson();
            }

            $responseDto = new GetAllDiscordMessagesResponseDto();
            $responseDto->prefillBaseFieldsForSuccessResponse();
            $responseDto->setDiscordMessagesJsons($arrayOfDtosJsons);

            return $responseDto->toJsonResponse();
        }catch(Exception $e){
            $this->app->getLoggerService()->logThrowable($e);

            $message = $this->app->trans('pages.discord.history.messages.errors.couldNotGetAllMessages');

            $baseResponseDto = BaseApiResponseDto::buildInternalServerErrorResponse();
            $baseResponseDto->setMessage($message);

            return $baseResponseDto->toJsonResponse();
        }
    }

}