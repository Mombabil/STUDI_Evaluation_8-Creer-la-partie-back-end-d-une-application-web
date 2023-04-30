<?php

namespace App\Controller;

use App\Entity\Applications;
use App\Entity\Post;
use App\Entity\User;
use App\Form\EditUserType;
use App\Form\UserProfilType;
use App\Form\UserRecruteurType;
use App\Form\UserType;
use App\Form\ValidateUserType;
use App\Repository\ApplicationsRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    // READ
    #[Route('/user', name: 'user')]
    public function index(ManagerRegistry $doctrine, UserRepository $userRepository, PostRepository $postRepository, ApplicationsRepository $candidaturesRepository): Response
    {
        // POUR L'ADMINISTRATEUR
        // on recupere tout les users
        $userRepository = $doctrine->getRepository(User::class);
        $users = $userRepository->findAll();

        // POUR CONSULTANT, RECRUTEUR, CANDIDATS
        // on recupere tout les posts
        $postRepository = $doctrine->getRepository(Post::class);
        $posts = $postRepository->findAll();
        // on recupere toutes les candidatures
        $candidaturesRepository = $doctrine->getRepository(Applications::class);
        $candidatures = $candidaturesRepository->findAll();


        return $this->render('user/index.html.twig', [
            'users' => $users,
            'posts' => $posts,
            'candidatures' => $candidatures
        ]);
    }

    // CREATE
    // creation d'un utilisateur
    #[Route('/new', name: 'user_new')]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $doctrine): Response
    {
        $user = new User($userPasswordHasher);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // apres la création, on redirige l'utilisateur vers la page d'accueil
            return $this->redirectToRoute('home');
        }
        return $this->render('user/new.html.twig', [
            'user_form' => $form->createView(),
        ]);
    }

    // UPDATE
    // modification des roles/nom/prénom/email par l'admin
    #[Route('/user/edit/{id<\d+>}', name: 'edit-user')]
    public function update(Request $request, User $user, ManagerRegistry $doctrine): Response
    {
        // si l'utilisateur n'est pas connecté, redirige vers login
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $formEditUser = $this->createForm(EditUserType::class, $user);
        $formEditUser->handleRequest($request);

        if ($formEditUser->isSubmitted() && $formEditUser->isValid()) {

            // on passe le validatedAccount a true manuellement
            $user->setValidatedAccount(true);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user/edit.html.twig', [
            // on affiche la vue du formulaire
            'userForm' => $formEditUser->createView(),
            // protection du formulaire
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'post_item',
        ]);
    }

    // UPDATE
    // validation des candidats/recruteurs par le consultant
    #[Route('/user/validate/{id<\d+>}', name: 'validate-user')]
    public function validate(Request $request, User $user, ManagerRegistry $doctrine): Response
    {
        // si l'utilisateur n'est pas connecté, redirige vers login
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $formValidateUser = $this->createForm(ValidateUserType::class, $user);
        $formValidateUser->handleRequest($request);

        if ($formValidateUser->isSubmitted() && $formValidateUser->isValid()) {

            // on passe le validatedAccount a true manuellement
            $user->setValidatedAccount(true);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // on redirige l'utilisateur vers la page de l'utilisateur
            return $this->redirectToRoute('user');
        }

        return $this->render('user/validate.html.twig', [
            // on affiche la vue du formulaire
            'validateUserForm' => $formValidateUser->createView(),
            // protection du formulaire
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'post_item',
        ]);
    }

    // UPDATE
    // complétion du profil utilisateur pour les candidats/recruteurs
    #[Route('/user/profil/{id<\d+>}', name: 'edit-profil')]
    public function complete(User $user, Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger)
    {
        // si l'utilisateur n'est pas connecté, redirige vers login
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // on ne peut pas acceder aux modifications du compte d'un autre utilisateur, on redirige 
        if ($this->getUser() !== $user) {
            // on affiche un message flash d'erreur
            $this->addFlash(
                "error",
                "Vous ne pouvez pas modifier un compte qui ne vous appartient pas"
            );
            // on redirige l'utilisateur vers la page user
            return $this->redirectToRoute('user');
        }

        // formulaire du recruteur
        $recruteurForm = $this->createForm(UserRecruteurType::class, $user);
        $recruteurForm->handleRequest($request);

        if ($recruteurForm->isSubmitted() && $recruteurForm->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }


        // formulaire du candidat
        $candidatForm = $this->createForm(UserProfilType::class, $user);
        $candidatForm->handleRequest($request);

        if ($candidatForm->isSubmitted() && $candidatForm->isValid()) {

            // on gere l'upload du cv
            $cv = $candidatForm->get('cv')->getData();

            if ($cv) {
                $originalFilename = pathinfo($cv->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $cv->guessExtension();


                try {
                    $cv->move(
                        $this->getParameter('uploads'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    dump($e);
                }


                $user->setCv($newFilename);

                $entityManager = $doctrine->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('home');
            }
        }

        return $this->render('user/profil.html.twig', [
            'user' => $user,
            'userRecruteur_form' => $recruteurForm->createView(),
            'userProfil_form' => $candidatForm->createView(),
            // protection du formulaire
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'post_item',
        ]);
    }

    // si l'utilisateur n'a pas encore été validé par le consultant, on l'envoi sur la page profil
    #[Route('/profil', name: 'app_profil')]
    public function profil(): Response
    {
        return $this->render('profil/index.html.twig');
    }
}
