<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{

    #[Route('/article/{id}', name: 'app_article')]
    public function index(int $id, PostRepository $postRepository): Response
    {
        $post = $postRepository->findOneBy(['id' => $id]);
        return $this->render('article/index.html.twig', [
            'post' => $post,
        ]);
    }
}
