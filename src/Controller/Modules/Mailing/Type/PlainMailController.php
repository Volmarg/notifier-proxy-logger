<?php

namespace App\Controller\Modules\Mailing\Type;

use App\Controller\Modules\Mailing\MailAccountController;
use App\Entity\Modules\Mailing\Mail;
use App\Entity\Modules\Mailing\MailAccount;
use Exception;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Handles sending plain E-mail:
 * - {@see Mail::isPlainEmail()}
 * - {@see MailerInterface}
 * - {@link https://symfony.com/doc/5.3/mailer.html#creating-sending-messages}
 */
class PlainMailController extends MailTypeController
{

    /**
     * @var EventDispatcherInterface $eventDispatcher
     */
    private EventDispatcherInterface $eventDispatcher;

    /**
     * @var MailAccountController $mailAccountController
     */
    private MailAccountController $mailAccountController;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param MailAccountController    $mailAccountController
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, MailAccountController $mailAccountController)
    {
        $this->mailAccountController = $mailAccountController;
        $this->eventDispatcher       = $eventDispatcher;
    }

    /**
     * Will return {@see Mailer} client used for sending E-Mails
     *
     * @param string $dsnString
     * @return Mailer
     */
    public function getMailerClientForDsn(string $dsnString): Mailer
    {
        $stopWatch   = new Stopwatch(true);
        $dispatcher  = new TraceableEventDispatcher($this->eventDispatcher, $stopWatch);
        $transport   = Transport::fromDsn($dsnString, $dispatcher);
        $mailer      = new Mailer($transport);

        return $mailer;
    }

    /**
     * Will return mailer client which uses the default {@see MailAccount}
     *
     * @return Mailer
     * @throws Exception
     */
    public function getMailerClientForDefaultAccount(): Mailer
    {
        $defaultMailAccount = $this->mailAccountController->getDefaultMailAccount();
        $mailer             = $this->getMailerClientForAccount($defaultMailAccount);

        return $mailer;
    }

    /**
     * @param MailAccount $mailAccount
     *
     * @return Mailer
     */
    public function getMailerClientForAccount(MailAccount $mailAccount): Mailer
    {
        $dsn    = $this->buildSymfonyMailerDsnConnectionString($mailAccount);
        $mailer = $this->getMailerClientForDsn($dsn);

        return $mailer;
    }
}