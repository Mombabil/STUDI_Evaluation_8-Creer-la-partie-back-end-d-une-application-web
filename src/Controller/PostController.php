<?php

namespace App\Controller;

use App\Entity\Applications;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\ApplicationsRepository;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    // READ
    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $doctrine, PostRepository $repository, ApplicationsRepository $repositoryForCandidatures): Response
    {
        // on recupère toutes les offres d'emplois créer
        $repository = $doctrine->getRepository(Post::class);
        $offers = $repository->findAll();

        // on récupère les candidatures pour gérer le bouton à afficher
        $repositoryForCandidatures = $doctrine->getRepository(Applications::class);
        $candidatures = $repositoryForCandidatures->findAll();



        return $this->render('post/index.html.twig', [
            'offers' => $offers,
            'candidatures' => $candidatures
        ]);
    }

    // CREATE
    #[Route('/offers/new')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        // si l'utilisateur n'est pas connecté, redirige vers login
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $offer = new Post();
        // on crée le formulaire
        $form = $this->createForm(PostType::class, $offer);
        $form->handleRequest($request);

        // si le formulaire est soumis ET valide
        if ($form->isSubmitted() && $form->isValid()) {

            // on insere manuellement l'id du recruteur qui a posté l'annonce
            $offer->setUser($this->getUser());
            // on insere manuellement la date de publication
            $offer->setPublishedAt(new \DateTime());
            // on push l'offre crée dans la base de donnée
            $entityManager = $doctrine->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();

            // puis on redirige vers la page d'accueil
            return $this->redirectToRoute('home');
        }

        return $this->render('post/form.html.twig', [
            'offer_form' => $form->createView(),
            // protection du formulaire
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'post_item',
        ]);
    }

    // UPDATE
    // l'offre publié doit être validé par le consultant apres sa creation
    #[Route('offers/validate/{id<\d+>}')]
    public function validate(Post $post, ManagerRegistry $doctrine): Response
    {
        // on passe la validation sur true et on push la modification dans la bdd
        $post->setIsValidated(true);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($post);
        $entityManager->flush();


        return $this->redirectToRoute('user');
    }
}
