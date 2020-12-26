<?php

namespace App\Controller\Modules\Mailing;

use App\Controller\Application;
use App\DTO\Modules\Mailing\MailDTO;
use App\Entity\Modules\Mailing\Mail;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailController extends AbstractController
{

    /**
     * @var Application $app
     */
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
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

}