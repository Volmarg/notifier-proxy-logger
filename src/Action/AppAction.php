<?php


namespace App\Action;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class for handling any specific system wide routes
 *
 * Class AppAction
 * @package App\Action
 */
class AppAction extends AbstractController
{

    /**
     * Will redirect user from home page to dashboard
     */
    #[Route("/", name: "home", methods: ["GET"])]
    public function index(): Response
    {
        return $this->redirectToRoute('modules_dashboard_overview');
    }

}