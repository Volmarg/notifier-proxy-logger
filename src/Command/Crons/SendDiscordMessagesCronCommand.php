<?php

namespace App\Command\Crons;


use App\Controller\Application;
use App\Controller\Core\Controllers;
use App\Entity\Modules\Discord\DiscordMessage;
use App\Services\External\DiscordService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TypeError;

/**
 *
 * Class SendDiscordMessagesCronCommand
 * @package App\Command\Assets
 */
class SendDiscordMessagesCronCommand extends Command
{
    const COMMAND_LOGGER_PREFIX = "[SendDiscordMessagesCronCommand] ";

    protected static $defaultName = 'npl:cron:send-discord-messages';

    /**
     * @var Application $app
     */
    private $app;

    /**
     * @var Controllers
     */
    private Controllers $controllers;

    /**
     * @var DiscordService $discordService
     */
    private DiscordService $discordService;

    /**
     * @var SymfonyStyle $io
     */
    private $io = null;

    public function __construct(Application $app, Controllers $controllers, DiscordService $discordService, string $name = null) {
        parent::__construct($name);
        $this->app            = $app;
        $this->controllers    = $controllers;
        $this->discordService = $discordService;
    }

    protected function configure()
    {
        $this
            ->setDescription("This command will attempt to send all processable discord messages");
    }

    protected function initialize(InputInterface $input, OutputInterface $output) {
        parent::initialize($input, $output);
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->app->getLoggerService()->getLogger()->info(self::COMMAND_LOGGER_PREFIX . "Started processing discord messages");

        try{

            $allMessagesToProcess = $this->controllers->getDiscordMessageController()->getAllProcessableMessages();
            if( empty($allMessagesToProcess) ){
                $this->app->getLoggerService()->getLogger()->info(self::COMMAND_LOGGER_PREFIX . "There are no messages to process. Stopping here.");
                return 1;
            }

            $countOfMessagesToProcess = count($allMessagesToProcess);
            $this->app->getLoggerService()->getLogger()->info(self::COMMAND_LOGGER_PREFIX . "Found {$countOfMessagesToProcess} messages to process.");

            foreach($allMessagesToProcess as $discordMessage){

                try{

                    $response = $this->discordService->sendDiscordMessage($discordMessage->getDiscordWebhook(), $discordMessage);
                    if( !$response->isSuccess() ){
                        $this->controllers->getDiscordMessageController()->updateStatus($discordMessage, DiscordMessage::STATUS_ERROR);
                        continue;
                    }

                    $this->controllers->getDiscordMessageController()->updateStatus($discordMessage, DiscordMessage::STATUS_SENT);
                }catch(Exception|TypeError $e){
                    $this->controllers->getDiscordMessageController()->updateStatus($discordMessage, DiscordMessage::STATUS_ERROR);
                    $this->app->getLoggerService()->logThrowable($e);
                    // no throwing, going further with all other entities
                }
            }

        }catch(Exception|TypeError $e){
            $this->app->getLoggerService()->logThrowable($e);
            return 0;
        }

        $this->app->getLoggerService()->getLogger()->info(self::COMMAND_LOGGER_PREFIX . "Finished processing discord messages");
        return 1;
    }

}