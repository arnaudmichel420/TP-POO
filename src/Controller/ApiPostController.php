<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/posts', name: 'app_api_posts')]
final class ApiPostController extends AbstractController
{
    #[Route('/', name: 'findAll', methods: ['GET'])]
    public function findAll(EntityManagerInterface $entityManager): JsonResponse
    {
        $posts = $entityManager->getRepository(Post::class)->findAll();
        return $this->json($posts, 200, [], ['groups' => 'post:read']);
    }
    #[Route('/{id}', name: 'findOne', methods: ['GET'])]
    public function findOne(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $produit = $entityManager->getRepository(Post::class)->findOneBy(['id' => $id]);
        if (!$produit) {
            $produit = (['message' => "Cette article n'existe pas"]);
            return $this->json($produit, 404, []);
        }
        return $this->json($produit, 200, [], ['groups' => 'post:read']);
    }
}
