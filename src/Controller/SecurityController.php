<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager,
     MailerInterface $mailer, UserPasswordHasherInterface $passwordHasher, FlashBagInterface $flash) {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $passwordHasher->hashPassword($user, $user->getPassword());
            // $user->setUsername($user->getEmail());
            $user->setRoles(array("ROLE_USER"));
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

        //     $email = (new Email())
        //     ->from(new Address('maxime.pasc@gmail.com', 'Garage Pascal'))
        //     ->to($user->getEmail())
        //     ->subject("Confirmation d'inscription")
        //     ->text('Bienvenue chez nous '); 
            
        //    $mailer->send($email);
        //    $flash->add('success', 'votre inscription à bien été prise en compte. Merci');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // /**
    //  * @Route("/login", name="app_login")
    //  */
    // public function login() {
    //     return $this->render('security/login.html.twig');
    // }
    
}
