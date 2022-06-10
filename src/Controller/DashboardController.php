<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends CoreController
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_not_auth');
        }

        $this->checkAndUpdateDomainsList();

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'domain_list' => $this->listDomains(),
        ]);
    }
}
