<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\PostFixtures;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;
    private $postRepository;

    public function __construct(UserRepository $userRepository, PostRepository $postRepository)
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
    }
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 11; $i++) {
            $comment = new Comment();
            $comment->setText("Trop bien cette article ❤️ " . $i);
            $comment->setUser($this->userRepository->findOneByEmail("user" . $i . "@test.com"));
            $comment->setPost($this->postRepository->findOneByTitle("Titre de l'article " . $i));

            $manager->persist($comment);
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PostFixtures::class,
        ];
    }
}
