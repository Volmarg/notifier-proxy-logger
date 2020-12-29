<?php

namespace App\Action\Modules\Discord;

use App\Controller\Application;
use App\Controller\Core\Controllers;
use App\DTO\API\BaseApiResponseDto;
use App\DTO\API\Internal\GetAllDiscordWebhooksResponseDto;
use App\DTO\Modules\Discord\DiscordWebhookDto;
use App\Services\External\DiscordService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/modules/discord", name: "modules_discord_")]
class DiscordAction extends AbstractController
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
     * @var DiscordService $discordService
     */
    private DiscordService $discordService;

    public function __construct(Application $app, Controllers $controllers, DiscordService $discordService)
    {
        $this->app            = $app;
        $this->controllers    = $controllers;
        $this->discordService = $discordService;
    }

    #[Route("/send-test-message-discord")]
    public function testSending(): JsonResponse
    {
        // todo: needs to be finished,
        //  add the test page with form
        $msg = "Test **message** ";

        $discordWebhook = $this->controllers->getDiscordWebhookController()->getOneByWebhookName('test');
        $responseDto    = $this->discordService->sendDiscordMessage($discordWebhook, $msg);

        dump($responseDto);
        die();
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

}