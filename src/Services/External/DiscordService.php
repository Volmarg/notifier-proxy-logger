<?php

namespace App\Services\External;

use App\Controller\Application;
use App\DTO\API\External\DiscordWebhookResponseDto;
use App\Entity\Modules\Discord\DiscordMessage;
use App\Entity\Modules\Discord\DiscordWebhook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DiscordService extends AbstractController
{

    const REQUEST_FIELD_CONTENT  = "content";
    const REQUEST_FIELD_USERNAME = "username";

    const PING_TYPE_EVERYONE = "@everyone";

    const DISCORD_MESSAGE_TEMPLATE_DEFAULT = "discord/default-message-template.twig";

    /**
     * @var Application $app
     */
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Will send single discord message to the provided webhook
     *
     * @param DiscordWebhook $discordWebhook
     * @param DiscordMessage $discordMessage
     * @param string $discordMessageTemplate
     * @return DiscordWebhookResponseDto
     */
    public function sendDiscordMessage(DiscordWebhook $discordWebhook, DiscordMessage $discordMessage, string $discordMessageTemplate = self::DISCORD_MESSAGE_TEMPLATE_DEFAULT): DiscordWebhookResponseDto
    {
        $this->app->getLoggerService()->getLogger()->info("Started preparing request for sending discord message to hook.");

        $templateContent = $this->render($discordMessageTemplate, [
            "discordMessage" => $discordMessage,
        ])->getContent();

        $requestData = [
            self::REQUEST_FIELD_CONTENT  => $templateContent,
            self::REQUEST_FIELD_USERNAME => $discordWebhook->getUsername()
        ];

        $ch = curl_init( $discordWebhook->getWebhookUrl() );

        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if(
                !$httpCode
            ||  $httpCode >= Response::HTTP_INTERNAL_SERVER_ERROR
        ){
            $this->app->getLoggerService()->getLogger()->critical("Tried to call the discord webhook but something went wrong", [
                "calledUrl"         => $discordWebhook->getWebhookUrl(),
                "httpCodeResponse"  => $httpCode,
            ]);

            $responseMessage = $this->app->trans('pages.discord.testMessageSending.thereWasAnIssueWhileCallingTheDiscordWebhook', [
                "{{httpCode}}" => $httpCode,
            ]);

            $discordWebhookResponseDto = DiscordWebhookResponseDto::buildInternalServerErrorResponse();
            $discordWebhookResponseDto->setMessage($responseMessage);

            return $discordWebhookResponseDto;
        }

        $discordWebhookResponseDto = $this->handleDiscordResponse($response, $httpCode);

        $this->app->getLoggerService()->getLogger()->info("Finished sending discord message for hook");

        return $discordWebhookResponseDto;
    }

    /**
     * Will handle the response from discord webhook call - will build the response dto based on response content
     *
     * @param string $response
     * @param int $httpCode
     * @return DiscordWebhookResponseDto
     */
    private function handleDiscordResponse(string $response, int $httpCode): DiscordWebhookResponseDto
    {
        // By default discord returns empty string when everything is ok
        if(
                empty($response)
            &&  (
                        $httpCode >= 200
                    &&  $httpCode < 300
                )
        ){
            $message = $this->app->trans('pages.discord.testMessageSending.success');

            $discordWebhookResponseDto = new DiscordWebhookResponseDto();
            $discordWebhookResponseDto->prefillBaseFieldsForSuccessResponse();
            $discordWebhookResponseDto->setMessage($message);;

            return $discordWebhookResponseDto;
        }

        json_decode($response, true);
        if( JSON_ERROR_NONE !== json_last_error() ){
            $message = $this->app->trans('pages.discord.testMessageSending.jsonReturnedFromDiscordServerIsMalformed');

            $discordWebhookResponseDto = DiscordWebhookResponseDto::buildBadRequestErrorResponse();
            $discordWebhookResponseDto->setMessage($message);

            return $discordWebhookResponseDto;
        }

        $dto = DiscordWebhookResponseDto::fromJson($response);
        $dto->setCode($httpCode);

        if ($httpCode >= 300) {
            $this->app->getLoggerService()->getLogger()->critical("Got error response from discord server", [
                "response" => $response,
            ]);
        }

        return $dto;
    }

}