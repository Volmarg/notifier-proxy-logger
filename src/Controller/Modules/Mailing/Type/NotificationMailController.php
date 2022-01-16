<?php

namespace App\Controller\Modules\Mailing\Type;

use App\Controller\Core\ConfigLoaders;
use App\Controller\Modules\Mailing\MailAccountController;
use App\Entity\Modules\Mailing\Mail;
use App\Entity\Modules\Mailing\MailAccount;
use Exception;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Notifier\Channel\EmailChannel;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Notifier;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Handles logic related to the {@see Notification} and thus {@see Mail::TYPE_NOTIFICATION}
 */
class NotificationMailController
{
    public const MAIL_CHANNEL_NAME = "email";

    private const LOCALHOST_SENDMAIL_DSN = "sendmail://default";

    /**
     * @var ConfigLoaders $configLoaders
     */
    private ConfigLoaders $configLoaders;

    /**
     * @var MailAccountController $mailAccountController
     */
    private MailAccountController $mailAccountController;

    /**
     * @var EventDispatcherInterface $eventDispatcher
     */
    private EventDispatcherInterface $eventDispatcher;

    /**
     * @param ConfigLoaders            $configLoaders
     * @param MailAccountController    $mailAccountController
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(ConfigLoaders $configLoaders, MailAccountController $mailAccountController, EventDispatcherInterface $eventDispatcher)
    {
        $this->mailAccountController = $mailAccountController;
        $this->eventDispatcher       = $eventDispatcher;
        $this->configLoaders         = $configLoaders;
    }

    /**
     * Will return notifier instance for sending mail messages by using local sendmail package
     *
     * @return Notifier
     */
    public function getNotifierForSendingMailNotificationsByUsingLocalSendmail(): Notifier
    {
        $notifier = $this->buildNotifierForDsnString(self::LOCALHOST_SENDMAIL_DSN);
        return $notifier;
    }

    /**
     * Will return notifier instance for sending mail messages, uses MailAccount configuration
     *
     * @param MailAccount $mailAccount
     * @return Notifier
     */
    public function getNotifierForSendingMailNotifications(MailAccount $mailAccount): Notifier
    {
        $dsnConnectionString = $this->buildSymfonyMailerDsnConnectionString($mailAccount);
        $notifier            = $this->buildNotifierForDsnString($dsnConnectionString);
        return $notifier;
    }

    /**
     * Will return texter instance which uses the default mail account for sending messages
     *
     * @return Notifier
     * @throws Exception
     */
    public function getDefaultNotifierForSendingMailNotifications(): Notifier
    {
        $defaultMailAccount = $this->mailAccountController->getDefaultMailAccount();
        $notifier           = $this->getNotifierForSendingMailNotifications($defaultMailAccount);

        return $notifier;
    }

    /**
     * Will build the mailer (MAILER_DSN) connection string used internally by symfony
     *
     * @param MailAccount $mailAccount
     * @return string
     */
    private function buildSymfonyMailerDsnConnectionString(MailAccount $mailAccount): string
    {
        $dsnConnectionString = "{$mailAccount->getClient()}://{$mailAccount->getLogin()}:{$mailAccount->getPassword()}@localhost";
        return $dsnConnectionString;
    }

    /**
     * Will build the mailer (MAILER_DSN) connection string used internally by symfony
     *
     * @param string $dsnString
     * @return Notifier
     */
    private function buildNotifierForDsnString(string $dsnString): Notifier
    {
        $fromMail =$this->configLoaders->getSystemDataConfigLoader()->getFromMail();

        $stopWatch   = new Stopwatch(true);
        $dispatcher  = new TraceableEventDispatcher($this->eventDispatcher, $stopWatch);
        $transport   = Transport::fromDsn($dsnString, $dispatcher);
        $mailChannel = new EmailChannel($transport, null, $fromMail);
        $notifier    = new Notifier([self::MAIL_CHANNEL_NAME => $mailChannel]);

        return $notifier;
    }

}