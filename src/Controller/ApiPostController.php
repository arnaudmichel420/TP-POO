<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

// #[Route('/api/posts', name: 'app_api_posts_')]
final class ApiPostController extends AbstractController
{
    #[Route('/api/posts', name: 'app_api_posts_list', methods: ['GET'])]
    public function findAll(EntityManagerInterface $entityManager): JsonResponse
    {
        $posts = $entityManager->getRepository(Post::class)->findAll();
        return $this->json($posts, 200, [], ['groups' => 'post:read']);
    }
    #[Route('/api/posts/{id}', name: 'app_api_posts_item', methods: ['GET'])]
    public function findOne(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $post = $entityManager->getRepository(Post::class)->findOneBy(['id' => $id]);
        if (!$post) {
            return $this->json(['message' => "Cette article n'existe pas"], 404, []);
        }
        return $this->json($post, 200, [], ['groups' => 'post:read']);
    }
}
