<?php

namespace App\Controller\Setup;

use App\Controller\CoreController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SetupController extends  CoreController
{
    /** @var EntityManagerInterface  */
    public \Doctrine\ORM\EntityManager $em;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/setup", name="app_setup")
     */
    public function index(): Response
    {
        return $this->render('setup/index.html.twig', [
            'controller_name' => 'SetupController',
        ]);
    }
}