<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    /**
     * @Route("/service", name="services_index")
     */
    public function index(ServiceRepository $serviceRepository)
    {
        return $this->render('service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/service/create", name="services_create")
     */
    public function create(Request $request): Response
    {
        // Permet de vérifier si utilisateur connecté et si c'est un admin.
        // Si ce n'est pas un admin alors on le redirige vers l'index des services car il n'a rien à faire ici.
        // => Code copié collé sur les 3 routes du crud : create/edit/delete
        $user = $this->getUser();
        if(!$user OR !in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->redirectToRoute('services_index');
        }
        
        $service = new Service();

        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();
  
            return $this->redirectToRoute('services_index');
        }

        return $this->render('service/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/service/edit/{id}", name="services_edit")
     */
    public function edit(Request $request, Service $service)
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('services_index');
        }

        return $this->render('service/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/service/delete/{id}", name="services_delete")
     */
    public function delete($id, ServiceRepository $serviceRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $service = $serviceRepository->find($id);
        $em->remove($service);
        $em->flush();

        return $this->redirectToRoute('services_index');
    }
}
