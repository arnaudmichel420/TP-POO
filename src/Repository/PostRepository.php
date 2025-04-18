<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findOneByTitle(string $title): ?Post
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.title = :title')
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function findAllByCategory(string $category): array
    {
        return $this->createQueryBuilder('post')
            ->join('post.categories', 'category')
            ->where('category.id = :id')
            ->setParameter('id', $category)
            ->getQuery()
            ->getResult();
    }
}
