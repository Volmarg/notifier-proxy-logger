<?php

namespace App\Action\API\External;

use App\Controller\Application;
use App\Controller\Core\Controllers;
use App\DTO\API\BaseApiResponseDto;
use App\DTO\Modules\Discord\DiscordMessageDTO;
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
    #[Route("/discord/insert-message", name: "discord_insert_messages", methods: ["POST"])]
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

            $discordMessageDto = DiscordMessageDTO::fromJson($json);
            $discordMessage    = $this->controllers->getDiscordMessageController()->buildMailEntityFromDto($discordMessageDto);

            $this->controllers->getDiscordMessageController()->saveEntity($discordMessage);

            $message = $this->app->trans("api.external.general.messages.ok");
            $baseApiResponseDto->setMessage($message);

            return $baseApiResponseDto->toJsonResponse();
        }catch(NoEntityWasFoundException $noEntityExc){
            $this->app->getLoggerService()->logThrowable($noEntityExc);
            $message = $this->app->trans('api.external.general.messages.badRequest');

            $responseDto = BaseApiResponseDto::buildBadRequestErrorResponse();
            $responseDto->setMessage($message);

            return $responseDto->toJsonResponse();
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