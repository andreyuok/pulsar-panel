<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoreController extends AbstractController
{

    const ABS_PATH_TO_VHOSTS = '/etc/apache2/sites-available';

    /**
     * @Route("/core", name="app_core")
     */
    public function index(): Response
    {
        return $this->render('core/index.html.twig', [
            'controller_name' => 'CoreController',
        ]);
    }

    /**
     * @return array|false
     */
    protected function listDomains()
    {
        return $this->sanitizeDomainList(scandir(self::ABS_PATH_TO_VHOSTS));
    }

    /**
     * @param array $domainList
     * @return array
     */
    private function sanitizeDomainList(array $domainList): array
    {
        $domainsArray = [];
        foreach ($domainList as $domain) {
            if ($domain == '.' || $domain == '..') {
                continue;
            }
            $domainsArray[] = str_replace('.conf', '', $domain);
        }

        return $domainsArray;
    }
}

