<?php

namespace App\Controller;

use App\Entity\Domains;
use App\Form\DomainsType;
use App\Repository\DomainsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/domains/crud")
 */
class DomainsCrudController extends AbstractController
{
    /**
     * @Route("/", name="app_domains_crud_index", methods={"GET"})
     */
    public function index(DomainsRepository $domainsRepository): Response
    {
        return $this->render('domains_crud/index.html.twig', [
            'domains' => $domainsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_domains_crud_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DomainsRepository $domainsRepository): Response
    {
        $domain = new Domains();
        $form = $this->createForm(DomainsType::class, $domain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $domainsRepository->add($domain, true);

            return $this->redirectToRoute('app_domains_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('domains_crud/new.html.twig', [
            'domain' => $domain,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_domains_crud_show", methods={"GET"})
     */
    public function show(Domains $domain): Response
    {
        return $this->render('domains_crud/show.html.twig', [
            'domain' => $domain,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_domains_crud_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Domains $domain, DomainsRepository $domainsRepository): Response
    {
        $form = $this->createForm(DomainsType::class, $domain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $domainsRepository->add($domain, true);

            return $this->redirectToRoute('app_domains_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('domains_crud/edit.html.twig', [
            'domain' => $domain,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_domains_crud_delete", methods={"POST"})
     */
    public function delete(Request $request, Domains $domain, DomainsRepository $domainsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domain->getId(), $request->request->get('_token'))) {
            $domainsRepository->remove($domain, true);
        }

        return $this->redirectToRoute('app_domains_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
