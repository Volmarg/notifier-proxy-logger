<?php

namespace App\Controller\Modules\Mailing;

use App\Controller\Application;
use App\Entity\Modules\Mailing\Mail;
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
     * @param int $countOfReturnedEmails
     * @return Mail[]
     */
    public function getLastEmails(int $countOfReturnedEmails): array
    {
        return $this->app->getRepositories()->getMailRepository()->getLastEmails($countOfReturnedEmails);
    }
}