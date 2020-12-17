<?php


namespace App\Action;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * This class contains the global actions defined for Vue calls
 *
 * Class BaseAction
 * @package App\Action
 */
class BaseVueAction extends AbstractController
{
    #[Route("/modules/mailing/overview",    name: "modules_mailing_overview",   methods: ["GET"])]
    #[Route("/modules/dashboard/overview",  name: "modules_dashboard_overview", methods: ["GET"])]
    #[Route("/modules/mailing/history",     name: "modules_mailing_history",    methods:["GET"])]
    public function renderBaseTemplate(): Response
    {
        return $this->render('base.twig');
    }
}