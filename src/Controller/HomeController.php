<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CategoryRepository $categoryRepository, PostRepository $postRepository, Request $request): Response
    {
        $category = $request->query->get('category');
        if (!empty($category)) {
            $posts = $postRepository->findAllByCategory($category);
        } else {
            $posts = $postRepository->findAll();
        }


        $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
            'categories' => $categories
        ]);
    }
    // #[Route('/', name: 'app_home_filtered')]
    // public function filtered(CategoryRepository $categoryRepository, PostRepository $postRepository, Request $request): Response
    // {
    //     $category = $request->query->get('category');
    //     dump($category);
    //     $posts = $postRepository->findAllByCategory($category);
    //     $categories = $categoryRepository->findAll();

    //     return $this->render('home/index.html.twig', [
    //         'posts' => $posts,
    //         'categories' => $categories
    //     ]);
    // }
}
