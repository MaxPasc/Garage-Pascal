<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FaqController extends AbstractController
{
    /**
     * @Route("/faq", name="faq_index")
     */
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('faq/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

     /**
     * @Route("/faq/create", name="faq_create")
     */
    public function create(Request $request): Response
    {
        $question = new Question();

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $em->persist($question);
          $em->flush();

          return $this->redirectToRoute('faq_index');
      }
        
        return $this->render('faq/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
