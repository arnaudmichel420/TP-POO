<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Security\Voter\CommentVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ArticleController extends AbstractController
{

    #[Route('/article/{id}', name: 'app_article')]
    public function index(int $id, PostRepository $postRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = $postRepository->findOneBy(['id' => $id]);

        //handle form
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $editForms = [];
        $deleteForms = [];
        foreach ($post->getComments() as $commentToUpdate) {
            if ($commentToUpdate->getUser() === $this->getUser() || in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
                $editForms[$commentToUpdate->getId()] = $this->createForm(CommentType::class, $commentToUpdate)->createView();
                $deleteForms[$commentToUpdate->getId()] = $this->createFormBuilder()
                    ->setAction($this->generateUrl('comment_delete', ['id' => $commentToUpdate->getId()]))
                    ->setMethod('POST')
                    ->getForm()
                    ->createView();
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setPost($post);

            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('app_article', ['id' => $id]);
        }
        return $this->render('article/index.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'editForms' => $editForms,
            'deleteForms' => $deleteForms,
        ]);
    }
    #[Route('/comment/{id}/update', name: 'comment_update', methods: ['POST'])]
    #[IsGranted(CommentVoter::EDIT, subject: 'comment')]
    public function update(Comment $comment, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_article', ['id' => $comment->getPost()->getId()]);
        }

        return $this->redirectToRoute('app_article', ['id' => $comment->getPost()->getId()]);
    }
    #[Route('/comment/{id}/delete', name: 'comment_delete', methods: ['POST'])]
    #[IsGranted(CommentVoter::EDIT, subject: 'comment')]
    public function delete(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $articleId = $comment->getPost()->getId();
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_article', ['id' => $articleId]);
    }
}
