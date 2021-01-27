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
use App\Entity\Modules\Discord\DiscordWebhook;
use App\Form\Modules\Discord\SendTestDiscordMessageForm;
use App\Services\External\DiscordService;
use App\Services\Internal\FormService;
use App\Services\Internal\ValidationService;
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

    const KEY_ENTITY_ID    = 'entityId';
    const KEY_WEBHOOK_URL  = 'webhookUrl';
    const KEY_WEBHOOK_NAME = 'webhookName';
    const KEY_USERNAME     = 'username';
    const KEY_DESCRIPTION  = 'description';

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

    /**
     * @var ValidationService $validationService
     */
    private ValidationService $validationService;

    public function __construct(Application $app, Controllers $controllers, DiscordService $discordService, FormService $formService, ValidationService $validationService)
    {
        $this->app               = $app;
        $this->formService       = $formService;
        $this->controllers       = $controllers;
        $this->discordService    = $discordService;
        $this->validationService = $validationService;
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
            $message = $this->app->trans('api.internal.general.missingParameterInRequest', [
                "{{parameterName}}" => self::KEY_MESSAGE
            ]);
            $responseDto = DiscordWebhookResponseDto::buildBadRequestErrorResponse();

            $responseDto->setMessage($message);
            return $responseDto->toJsonResponse();
        }

        if( !array_key_exists(self::KEY_WEBHOOK_ID, $dataArray) ){
            $message = $this->app->trans('api.internal.general.missingParameterInRequest', [
                "{{parameterName}}" => self::KEY_WEBHOOK_ID
            ]);
            $responseDto = DiscordWebhookResponseDto::buildBadRequestErrorResponse();

            $responseDto->setMessage($message);
            return $responseDto->toJsonResponse();
        }

        if( !array_key_exists(self::KEY_TITLE, $dataArray) ){
            $message = $this->app->trans('api.internal.general.missingParameterInRequest', [
                "{{parameterName}}" => self::KEY_TITLE
            ]);
            $responseDto = DiscordWebhookResponseDto::buildBadRequestErrorResponse();

            $responseDto->setMessage($message);
            return $responseDto->toJsonResponse();
        }

        $webhookId      = $dataArray[self::KEY_WEBHOOK_ID];
        $webhookMessage = $dataArray[self::KEY_MESSAGE];
        $webhookTitle   = $dataArray[self::KEY_TITLE];

        // todo: remove up to this point, form should handle it

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
     * Handles the frontend (axios) request to update the discord webhook
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route("/update-webhook", name: "update_webhook", methods: ["POST"])]
    public function updateWebhook(Request $request): JsonResponse
    {
        try{
            $requestContentArray = json_decode($request->getContent(), true);
            if( !array_key_exists(self::KEY_WEBHOOK_URL, $requestContentArray) ){
                $message = $this->app->trans('api.internal.general.missingParameterInRequest', [
                    '{{parameterName}}' => self::KEY_WEBHOOK_URL,
                ]);

                return BaseApiResponseDto::buildBadRequestErrorResponse($message)->toJsonResponse();
            }

            if( !array_key_exists(self::KEY_WEBHOOK_NAME, $requestContentArray) ){
                $message = $this->app->trans('api.internal.general.missingParameterInRequest', [
                    '{{parameterName}}' => self::KEY_WEBHOOK_NAME,
                ]);

                return BaseApiResponseDto::buildBadRequestErrorResponse($message)->toJsonResponse();
            }

            if( !array_key_exists(self::KEY_USERNAME, $requestContentArray) ){
                $message = $this->app->trans('api.internal.general.missingParameterInRequest', [
                    '{{parameterName}}' => self::KEY_USERNAME,
                ]);

                return BaseApiResponseDto::buildBadRequestErrorResponse($message)->toJsonResponse();
            }

            if( !array_key_exists(self::KEY_DESCRIPTION, $requestContentArray) ){
                $message = $this->app->trans('api.internal.general.missingParameterInRequest', [
                    '{{parameterName}}' => self::KEY_DESCRIPTION,
                ]);

                return BaseApiResponseDto::buildBadRequestErrorResponse($message)->toJsonResponse();
            }

            if( !array_key_exists(self::KEY_ENTITY_ID, $requestContentArray) ){
                $message = $this->app->trans('api.internal.general.missingParameterInRequest', [
                    '{{parameterName}}' => self::KEY_ENTITY_ID,
                ]);

                return BaseApiResponseDto::buildBadRequestErrorResponse($message)->toJsonResponse();
            }

            $entityId    = $requestContentArray[self::KEY_ENTITY_ID];
            $webhookUrl  = $requestContentArray[self::KEY_WEBHOOK_URL];
            $webhookName = $requestContentArray[self::KEY_WEBHOOK_NAME];
            $username    = $requestContentArray[self::KEY_USERNAME];
            $description = $requestContentArray[self::KEY_DESCRIPTION];

            $discordWebhook = $this->controllers->getDiscordWebhookController()->getOneById($entityId);
            if( empty($discordWebhook) ){
                $message = $this->app->trans('pages.discord.updateDiscordWebhook.messages.fail.noDiscordWebhookWasFound');
                return BaseApiResponseDto::buildBadRequestErrorResponse($message)->toJsonResponse();
            }

            $discordWebhook->setUsername($username);
            $discordWebhook->setDescription($description);
            $discordWebhook->setWebhookName($webhookName);
            $discordWebhook->setWebhookUrl($webhookUrl);

            $violations = $this->validationService->validateAndReturnInvalidFieldsWithMessagesForResponse($discordWebhook);
            if( !empty($violations) ){
                $message = $this->app->trans('api.internal.general.fieldsViolations', [
                    '{{fieldsList}}' => $violations,
                ]);
                return BaseApiResponseDto::buildBadRequestErrorResponse($message)->toJsonResponse();
            }

            $this->controllers->getDiscordWebhookController()->save($discordWebhook);

            $successMessage = $this->app->trans('pages.discord.updateDiscordWebhook.messages.success');
            $baseResponseDto = new BaseApiResponseDto();
            $baseResponseDto->prefillBaseFieldsForSuccessResponse();
            $baseResponseDto->setMessage($successMessage);
        }catch(Exception $e){
            $this->app->getLoggerService()->logThrowable($e);

            $message = $this->app->trans('pages.discord.updateDiscordWebhook.messages.fail.failedToUpdate');

            $baseResponseDto = BaseApiResponseDto::buildInternalServerErrorResponse();
            $baseResponseDto->setMessage($message);

            return $baseResponseDto->toJsonResponse();
        }

        return $baseResponseDto->toJsonResponse();
    }

    /**
     * Handles the removal of single webhook
     *
     * @param string $webhookId
     * @return JsonResponse
     */
    #[Route("/remove-webhook/{webhookId}", name: "remove_webhook", methods: [ "GET" ])]
    public function removeWebhook(string $webhookId): JsonResponse
    {
        try{
            $discordWebhook = $this->controllers->getDiscordWebhookController()->getOneById($webhookId);
            if( empty($discordWebhook) ){
                $message = $this->app->trans('pages.discord.removeDiscordWebhook.messages.fail.noDiscordWebhookWasFound');
                return BaseApiResponseDto::buildBadRequestErrorResponse($message)->toJsonResponse();
            }

            $this->controllers->getDiscordWebhookController()->hardDelete($discordWebhook);

            $successMessage = $this->app->trans('pages.discord.removeDiscordWebhook.messages.success');
            $baseResponseDto = new BaseApiResponseDto();
            $baseResponseDto->prefillBaseFieldsForSuccessResponse();
            $baseResponseDto->setMessage($successMessage);
        }catch(Exception $e){
            $this->app->getLoggerService()->logThrowable($e);

            $message = $this->app->trans('pages.discord.removeDiscordWebhook.messages.fail.failedToRemove');

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