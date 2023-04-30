<?php

namespace App\Controller;

use App\Entity\Applications;
use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends AbstractController
{
    #[Route('/application/{id}', name: 'app_application')]
    public function candidature(Post $post, ManagerRegistry $doctrine): Response
    {
        // si l'utilisateur n'est pas connecté, redirige vers login
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // on affiche un message flash d'erreur
        $this->addFlash(
            "success",
            "Merci d'avoir postulé."
        );

        $candidature = new Applications();

        $candidature->setOffer($post->getId());
        $candidature->setUser($this->getUser()->getId());
        $candidature->setWorkplace($post->getWorkplace());
        $candidature->setTitle($post->getTitle());
        $candidature->setUsername($this->getUser()->getName());
        $candidature->setUserfirstname($this->getUser()->getFirstname());
        $candidature->setEmail($this->getUser()->getEmail());
        $candidature->setRecruteur($post->getUser()->getEmail());
        $candidature->setCv($this->getUser()->getCv());

        $entityManager = $doctrine->getManager();
        $entityManager->persist($candidature);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

    // validation de la candidature par le consultant
    // envoi d'un mail au recruteur contenant le cv et la candidature du candidat
    #[Route('application/validate/{id}')]
    public function validate(Applications $candidature, ManagerRegistry $doctrine, MailerInterface $mailer): Response
    {
        // on envoi un mail au recruteur de la part du candidat
        $mail = (new TemplatedEmail())
            ->from(new Address(
                $candidature->getEmail(),
                'adresse mail du candidat'
            ))
            ->to($candidature->getRecruteur())
            ->subject('Ma candidature')
            ->htmlTemplate('mail/template.html.twig')
            ->context([
                'username' => $candidature->getUsername(),
                'firstname' => $candidature->getUserfirstname(),
                'offer' => $candidature->getTitle()
            ])
            // on envoi le cv du candidat
            ->addPart(new DataPart(new File('uploads/' . $candidature->getCv())));

        $mailer->send($mail);

        // on passe la validation sur true et on push la modification dans la bdd
        $candidature->setIsValidated(true);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($candidature);
        $entityManager->flush();

        return $this->redirectToRoute('user');
    }
}
