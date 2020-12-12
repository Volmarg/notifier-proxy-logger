<?php

namespace App\Action\Modules\Mailing;

use App\Controller\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/modules/mailing", name: "modules_mailing_")]
class MailingAction extends AbstractController
{
    /**
     * @var Application $application
     */
    private Application $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

}