<?php

namespace App\Action\API\External;

use App\Attributes\IsApiRoute;
use App\Controller\Application;
use App\Controller\Core\Controllers;
use App\DTO\API\BaseApiResponseDto;
use App\DTO\Modules\Discord\DiscordMessageDTO;
use App\Entity\Modules\Discord\DiscordWebhook;
use App\Exception\NoEntityWasFoundException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TypeError;

#[Route("/api/external", name: "api_external_")]
class DiscordExternalApiAction extends AbstractController
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
    #[IsApiRoute]
    #[Route("/discord/insert-message", name: "discord_insert_messages", methods: ["POST"])]
    public function insertDiscordMessage(Request $request): JsonResponse
    {
        try{
            $this->app->getLoggerService()->getLogger()->info("API method has been called: ", [
                __CLASS__ . "::" . __METHOD__,
            ]);

            $baseApiResponseDto  = new BaseApiResponseDto();
            $baseApiResponseDto->prefillBaseFieldsForSuccessResponse();

            $json = $request->getContent();

            json_decode($json, true);
            if( JSON_ERROR_NONE !== json_last_error() ){
                $this->app->getLoggerService()->getLogger()->info("Provided json has invalid syntax", [
                    "json_error" => json_last_error_msg(),
                    "json"       => $json,
                ]);
                $message = $this->app->trans("api.external.general.messages.invalidJsonSyntax");
                $baseApiResponseDto->prefillBaseFieldsForBadRequestResponse();
                $baseApiResponseDto->setMessage($message);

                return $baseApiResponseDto->toJsonResponse();
            }

            //todo: adjust, now it's needed to provided the webhook name

            $discordMessageDto = DiscordMessageDTO::fromJson($json);
            $discordMessage    = $this->controllers->getDiscordMessageController()->buildDiscordMessageEntityFromDto($discordMessageDto);

            $this->controllers->getDiscordMessageController()->saveEntity($discordMessage);

            $message = $this->app->trans("api.external.general.messages.ok");
            $baseApiResponseDto->setMessage($message);

            $this->app->getLoggerService()->getLogger()->info("Api call finished with success");
            return $baseApiResponseDto->toJsonResponse();
        }catch(NoEntityWasFoundException $noEntityExc){
            $this->app->getLoggerService()->logThrowable($noEntityExc);
            $message = $this->app->trans('api.external.general.messages.noEntityHasBeenFound', [
                "{{entityClass}}" => DiscordWebhook::class
            ]);

            $responseDto = BaseApiResponseDto::buildBadRequestErrorResponse();
            $responseDto->setMessage($message);

            return $responseDto->toJsonResponse();
        }catch(Exception| TypeError $e){
            $this->app->getLoggerService()->logThrowable($e, [
                "info" => "Issue occurred while handling external API method for inserting discord message"
            ]);
            $message = $this->app->trans('api.external.general.messages.internalServerError');

            $responseDto = BaseApiResponseDto::buildInternalServerErrorResponse();
            $responseDto->setMessage($message);

            return $responseDto->toJsonResponse();
        }
    }

}