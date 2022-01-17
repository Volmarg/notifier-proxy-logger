<?php

namespace App\Controller\Modules\Mailing;

use App\Controller\Application;
use App\Controller\Modules\Mailing\Type\NotificationMailController;
use App\DTO\Modules\Mailing\MailDTO;
use App\Entity\Modules\Mailing\Mail;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Notifier;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

/**
 * Handles general logic related to CRUD on {@see Mail} + generic sending logic
 */
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

    private MailAttachmentController $attachmentController;

    public function __construct(Application $app, NotifierInterface $notifier, MailAttachmentController $attachmentController)
    {
        $this->attachmentController = $attachmentController;
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
     * Will send single email via {@see NotifierInterface}, which means it add some extra styling etc.
     *
     * @param Mail $mail
     * @param Notifier $notifier
     */
    public function sendSingleEmailViaNotifier(Mail $mail, Notifier $notifier)
    {
        $notification = new Notification();
        $notification->subject($mail->getSubject());
        $notification->content($mail->getBody());
        $notification->channels([NotificationMailController::MAIL_CHANNEL_NAME]);

        foreach($mail->getToEmails() as $singleEmailString){
            $notificationRecipient = new Recipient($singleEmailString);
            $notifier->send($notification, $notificationRecipient);
        }

    }

    /**
     * Will send single email via {@see MailerInterface}, no extra formatting from symfony etc.,
     *
     * @param Mail   $mail
     * @param Mailer $mailer
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransportExceptionInterface
     */
    public function sendSingleEmailViaMailer(Mail $mail, Mailer $mailer): void
    {
        $txt   = strip_tags($mail->getBody());
        $email = (new Email())
            ->from($mail->getFromEmail())
            ->replyTo($mail->getFromEmail())
            ->to(...$mail->getToEmails())
            ->subject($mail->getSubject())
            ->text($txt)
            ->html($mail->getBody());

        $email = $this->attachmentController->base64HtmlIntoEmbeddedImage($email, MailAttachmentController::CONTENT_TYPE_HTML);
        $email = $this->attachmentController->base64HtmlIntoEmbeddedImage($email, MailAttachmentController::CONTENT_TYPE_TEXT);
        $email = $this->attachmentController->attachFiles($mail, $email);

        $mail->setParsedBody($email->getHtmlBody());
        $this->saveEntity($mail);

        $mailer->send($email);
    }

    /**
     * Will either return {@see Mail} entity for an id or null if nothing is found
     *
     * @param int $id
     * @return Mail|null
     */
    public function findOne(int $id): ?Mail
    {
        return $this->app->getRepositories()->getMailRepository()->find($id);
    }

}