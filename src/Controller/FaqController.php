<?php

namespace App\Controller;


use App\Entity\Faq;
use App\Form\FaqType;
use App\Repository\FaqRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FaqController extends AbstractController
{
    /**
     * @Route("/faq", name="faq_index")
     */
    public function index(FaqRepository $faqRepository): Response
    {
        return $this->render('faq/index.html.twig', [
            'faqs' => $faqRepository->findBy(['visible' => true], ['weight' => 'desc']),
        ]);
    }

     /**
     * @Route("/faq/create", name="faq_create")
     */
    public function create(Request $request): Response
    {
        $faq = new Faq();

        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $user = $this->getUser();
          $em = $this->getDoctrine()->getManager();
          $em->persist($faq);
          $faq->setUser($user);
          $em->flush();

          return $this->redirectToRoute('faq_index');
      }
        
        return $this->render('faq/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
