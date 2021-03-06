<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InitController extends CoreController
{
    /**
     * @Route("/", name="app_init")
     */
    public function index(): Response
    {
        return $this->render('init/index.html.twig', [
            'controller_name' => 'InitController',
        ]);
    }
}
