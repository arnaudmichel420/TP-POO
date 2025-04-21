<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    function __construct(private PostRepository $postRepository, private EntityManagerInterface $entityManager) {}
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $posts = $this->postRepository->findAll();

        $deleteForms = [];
        foreach ($posts as $post) {
            $deleteForms[$post->getId()] = $this->createFormBuilder()
                ->setAction($this->generateUrl('app_admin_delete', ['id' => $post->getId()]))
                ->setMethod('POST')
                ->getForm()
                ->createView();
        }


        return $this->render('admin/index.html.twig', [
            'posts' => $posts,
            'deleteForms' => $deleteForms,
        ]);
    }
    #[Route('/admin/create', name: 'app_admin_create')]
    public function create(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($form);
            $post->setUser($this->getUser());

            $this->entityManager->persist($post);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_admin');
        }


        return $this->render('admin/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/admin/{id}/update', name: 'app_admin_update')]
    public function update(int $id, Request $request): Response
    {
        $post = $this->postRepository->findOneBy(["id" => $id]);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/admin/{id}/delete', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Post $post, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin');
    }
}
