<?php

namespace App\Action\Modules\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/modules/dashboard", name: "dashboard_")]
class DashboardAction extends AbstractController
{

    #[Route("/overview", name: "overview", methods: ["GET"])]
    /**
     * @return Response
     */
    public function showDashboardPage(): Response
    {
        return $this->render('modules/dashboard/overview.twig');
    }

}