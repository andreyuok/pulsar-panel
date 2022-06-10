<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotAuthController extends AbstractController
{
    /**
     * @Route("/not/auth", name="app_not_auth")
     */
    public function index(): Response
    {
        return $this->render('not_auth/index.html.twig', [
            'controller_name' => 'NotAuthController',
        ]);
    }
}
