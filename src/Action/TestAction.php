<?php

namespace App\Action;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestAction extends AbstractController
{
    #[Route('/test' , name: 'test', methods: ["GET"])]
    public function testView()
    {

        return $this->render('base.twig');
    }

}