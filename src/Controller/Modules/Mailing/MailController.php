<?php

namespace App\Controller\Modules\Mailing;

use App\Controller\Application;
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
     * @return array
     */
    public function getAllEmails(): array
    {
        return $this->app->getRepositories()->getMailRepository()->getAllEmails();
    }
}