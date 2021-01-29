<?php

namespace App\Controller\Modules\Mailing;

use App\Controller\Application;
use App\DTO\Modules\Mailing\MailDTO;
use App\Entity\Modules\Mailing\Mail;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Notifier;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class MailController extends AbstractController
{

    /**
     * @var Application $app
     */
    private Application $app;

    /**
     * @var NotifierInterface $notifier
     */
    private NotifierInterface $notifier;

    public function __construct(Application $app, NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
        $this->app      = $app;
    }

    /**
     * Will return all Emails
     *
     * @return Mail[]
     */
    public function getAllEmails(): array
    {
        return $this->app->getRepositories()->getMailRepository()->getAllEmails();
    }

    /**
     * Will return the last processed Emails ordered by the creation date, reduced to the given count
     *
     * @param int $countOfReturnedEmails
     * @return Mail[]
     */
    public function getLastEmails(int $countOfReturnedEmails): array
    {
        return $this->app->getRepositories()->getMailRepository()->getLastEmails($countOfReturnedEmails);
    }

    /**
     * Will build Mail entity from MailDto
     *
     * @param MailDTO $mailDto
     * @return Mail
     * @throws Exception
     */
    public function buildMailEntityFromMailDto(MailDto $mailDto): Mail
    {
        $mail = new Mail();
        $mail->setSource($mailDto->getSource());
        $mail->setToEmails($mailDto->getToEmails());
        $mail->setFromEmail($mailDto->getFromEmail());
        $mail->setSubject($mailDto->getSubject());
        $mail->setBody($mailDto->getBody());

        return $mail;
    }

    /**
     * Will save the entity state, creates new DB entry if no such entity exists or updates state of the existing one
     *
     * @param Mail $mail
     * @return Mail
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveEntity(Mail $mail): Mail
    {
        return $this->app->getRepositories()->getMailRepository()->saveEntity($mail);
    }

    /**
     * Will return all the emails that can be processed further
     *
     * @return Mail[]
     */
    public function getAllProcessableEmails(): array
    {
        return $this->app->getRepositories()->getMailRepository()->getAllProcessableEmails();
    }

    /**
     * Will set given status to the entity and will update it the database
     *
     * @param Mail $mail
     * @param string $status
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateStatus(Mail $mail, string $status): void
    {
        $mail->setStatus($status);
        $this->saveEntity($mail);
    }

    /**
     * Will send single email
     *
     * @param Mail $mail
     * @param Notifier $notifier
     */
    public function sendSingleEmailViaNotifier(Mail $mail, Notifier $notifier)
    {
        $notification = new Notification();
        $notification->subject($mail->getSubject());
        $notification->content($mail->getBody());
        $notification->channels([MailAccountController::MAIL_CHANNEL_NAME]);

        foreach($mail->getToEmails() as $singleEmailString){
            $notificationRecipient = new Recipient($singleEmailString);
            $notifier->send($notification, $notificationRecipient);
        }

    }
}