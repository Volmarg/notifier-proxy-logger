<?php

namespace App\Command\Crons;

use App\Controller\Application;
use App\Controller\Core\Controllers;
use App\Controller\Modules\Mailing\Type\NotificationMailController;
use App\Controller\Modules\Mailing\Type\PlainMailController;
use App\Entity\Modules\Mailing\Mail;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use TypeError;

/**
 * Class SendEmailsCronCommand
 * @package App\Command\Assets
 */
class SendEmailsCronCommand extends Command
{
    const COMMAND_LOGGER_PREFIX = "[SendEmailsCronCommand] ";

    protected static $defaultName = 'npl:cron:send-emails';

    /**
     * @var Application $app
     */
    private $app;

    /**
     * @var Controllers
     */
    private Controllers $controllers;

    /**
     * @var SymfonyStyle $io
     */
    private $io = null;

    /**
     * @var NotificationMailController $notificationMailController
     */
    private NotificationMailController $notificationMailController;

    /**
     * @var PlainMailController $plainMailController
     */
    private PlainMailController $plainMailController;

    /**
     * @param Application                $app
     * @param Controllers                $controllers
     * @param NotificationMailController $notificationMailController
     * @param PlainMailController        $plainMailController
     * @param string|null                $name
     */
    public function __construct(
        Application $app,
        Controllers $controllers,
        NotificationMailController $notificationMailController,
        PlainMailController        $plainMailController,
        string $name = null
    ) {
        parent::__construct($name);
        $this->app                        = $app;
        $this->controllers                = $controllers;
        $this->plainMailController        = $plainMailController;
        $this->notificationMailController = $notificationMailController;
    }

    protected function configure()
    {
        $this
            ->setDescription("This command will attempt to send all processable emails");
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
        $this->app->getLoggerService()->getLogger()->info(self::COMMAND_LOGGER_PREFIX . "Started processing emails to send");

        try{

            $allEmailsToProcess = $this->controllers->getMailingController()->getAllProcessableEmails();
            if( empty($allEmailsToProcess) ){
                $this->app->getLoggerService()->getLogger()->info(self::COMMAND_LOGGER_PREFIX . "There are no emails to process. Stopping here.");
                return 1;
            }

            $countOfEmailsToProcess = count($allEmailsToProcess);
            $this->app->getLoggerService()->getLogger()->info(self::COMMAND_LOGGER_PREFIX . "Found {$countOfEmailsToProcess} emails to process.");

            foreach($allEmailsToProcess as $email){

                try{
                    $this->sendEmail($email);
                    $this->controllers->getMailingController()->updateStatus($email, Mail::STATUS_SENT);
                }catch(Exception|TypeError $e){
                    $this->controllers->getMailingController()->updateStatus($email, Mail::STATUS_ERROR);
                    $this->app->getLoggerService()->logThrowable($e);
                    // no throwing, going further with all other entities
                }
            }

        }catch(Exception|TypeError $e){
            $this->app->getLoggerService()->logThrowable($e);
            return 0;
        }

        $this->app->getLoggerService()->getLogger()->info(self::COMMAND_LOGGER_PREFIX . "Finished processing emails to send");

        return 1;
    }

    /**
     * Will handle sending E-Mail
     *
     * @param Mail $email
     *
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    private function sendEmail(Mail $email): void
    {
       switch($email->getType())
       {
           case Mail::TYPE_NOTIFICATION:
           {
               $notifier = $this->notificationMailController->getDefaultNotifierForSendingMailNotifications();
               $this->controllers->getMailingController()->sendSingleEmailViaNotifier($email, $notifier);
           }
           break;

           case Mail::TYPE_PLAIN:
           {
               $mailer = $this->plainMailController->getMailerClientForDefaultAccount();
               $this->controllers->getMailingController()->sendSingleEmailViaMailer($email, $mailer);
           }
           break;

           default:
           {
               throw new \LogicException("This Mail type is not supported: {$email->getType()}");
           }
       }
    }

}