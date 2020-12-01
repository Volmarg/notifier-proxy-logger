<?php

namespace App\Action\Modules\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardAction extends AbstractController
{

    #[Route("/dashboard", name: "dashboard", methods: ["GET"])]
    /**
     * @return Response
     */
    public function showDashboardPage(): Response
    {
        return $this->render('base.twig');
    }

}