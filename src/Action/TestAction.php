<?php

namespace App\Action;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestAction
{
    #[Route('/test' , name: 'test', methods: ["GET"])]
    public function testView()
    {

        return new Response("nope");
    }

}