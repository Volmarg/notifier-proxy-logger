<?php


namespace App\Controller\Modules\Mailing;


use App\Controller\Application;
use App\Entity\Modules\Mailing\MailAccount;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Notifier\Channel\EmailChannel;
use Symfony\Component\Notifier\Notifier;
use Symfony\Component\Stopwatch\Stopwatch;

class MailAccountController extends AbstractController
{
    const MAIL_CHANNEL_NAME = "email";

    /**
     * @var Application $app
     */
    private Application $app;

    /**
     * @var EventDispatcherInterface $eventDispatcher
     */
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(Application $app, EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->app             = $app;
    }

    /**
     * Will create new entity if such one does not exist or update the existing one
     *
     * @param MailAccount $mailAccount
     * @return MailAccount
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveMailAccount(MailAccount $mailAccount): MailAccount
    {
        return $this->app->getRepositories()->getMailAccountRepository()->saveMailAccount($mailAccount);
    }

    /**
     * Wil return the default mail account
     *
     * @return MailAccount
     * @throws Exception
     */
    public function getDefaultMailAccount(): MailAccount
    {
        $mailAccount = $this->app->getRepositories()->getMailAccountRepository()->getDefaultMailAccount();
        if( empty($mailAccount) ){
            throw new Exception("Default mail account was not found in the database!");
        }

        return $mailAccount;
    }

    /**
     * Will return all mail accounts
     *
     * @return MailAccount[]
     */
    public function getAllMailAccounts(): array
    {
        return $this->app->getRepositories()->getMailAccountRepository()->getAllMailAccounts();
    }

    /**
     * Will return one mail account or null if none for given id was found
     *
     * @param string $id
     * @return MailAccount|null
     */
    public function getOneById(string $id): ?MailAccount
    {
        return $this->app->getRepositories()->getMailAccountRepository()->getOneById($id);
    }

    /**
     * Will hard delete the entity, and assign them to placeholder webhook
     *
     * @param MailAccount $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function hardDelete(MailAccount $entity): void
    {
        $this->app->getRepositories()->getMailAccountRepository()->hardDelete($entity);
    }

    /**
     * Will save entity if it's a new one, or update already existing
     *
     * @param MailAccount $mailAccount
     * @return MailAccount
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(MailAccount $mailAccount): MailAccount
    {
        return $this->app->getRepositories()->getMailAccountRepository()->save($mailAccount);
    }

    /**
     * Will return texter instance which uses the default mail account for sending messages
     *
     * @throws Exception
     * @return Notifier
     */
    public function getDefaultNotifierForSendingMailNotifications(): Notifier
    {
        $defaultMailAccount = $this->getDefaultMailAccount();
        $notifier           = $this->getNotifierForSendingMailNotifications($defaultMailAccount);

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
        $fromMail = $this->app->getConfigLoaders()->getSystemDataConfigLoader()->getFromMail();

        $dsnConnectionString = $this->buildSymfonyMailerDsnConnectionString($mailAccount);
        $stopWatch           = new Stopwatch(true);
        $dispatcher          = new TraceableEventDispatcher($this->eventDispatcher, $stopWatch);
        $transport           = Transport::fromDsn($dsnConnectionString, $dispatcher);
        $mailChannel         = new EmailChannel($transport, null, $fromMail);
        $notifier            = new Notifier([self::MAIL_CHANNEL_NAME => $mailChannel]);
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

}