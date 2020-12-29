<?php

namespace App\Services\External;

use App\Controller\Application;
use App\DTO\API\External\DiscordWebhookResponseDto;
use App\Entity\Modules\Discord\DiscordWebhook;

class DiscordService
{

    const REQUEST_FIELD_CONTENT  = "content";
    const REQUEST_FIELD_USERNAME = "username";

    const PING_TYPE_EVERYONE = "@everyone";

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
     * @param string $message
     * @return DiscordWebhookResponseDto
     */
    public function sendDiscordMessage(DiscordWebhook $discordWebhook, string $message): DiscordWebhookResponseDto
    {
        $this->app->getLoggerService()->getLogger()->info("Started preparing request for sending discord message to hook.");

        $requestData = [
            self::REQUEST_FIELD_CONTENT  => $message,
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
            return DiscordWebhookResponseDto::buildOkResponse();
        }

        json_decode($response, true);
        if( JSON_ERROR_NONE !== json_last_error() ){
            return DiscordWebhookResponseDto::buildBadRequestResponse();
        }

        $dto = DiscordWebhookResponseDto::fromJson($response);
        $dto->setCode($httpCode);

        return $dto;
    }

}