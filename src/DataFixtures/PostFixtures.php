<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\CategoryFixtures;
use App\Entity\Post;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;
    private $categoryRepository;

    private $data;

    public function __construct(UserRepository $userRepository, CategoryRepository $categoryRepository, private HttpClientInterface $client)
    {
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;


        $pexelApiKey = $_ENV['PEXEL_API_KEY'] ?? null;
        $response = $this->client->request('GET', 'https://api.pexels.com/v1/search?query=voyage&per_page=10', [
            'headers' => [
                'Authorization' => $pexelApiKey,
            ],
        ]);
        $this->data = $response->toArray();
    }


    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 11; $i++) {

            $post = new Post();
            $post->setTitle("Titre de l'article " . $i);
            $post->setImg($this->data['photos'][$i - 1]['url']);
            $post->setText($i . " Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore earum asperiores tenetur voluptas nobis blanditiis minus perferendis mollitia voluptates, eligendi nemo libero vero excepturi voluptatem numquam a sapiente quibusdam! Dolores.");
            $post->setUser($this->userRepository->findOneByEmail("user" . $i . "@test.com"));
            $post->addCategory($this->categoryRepository->findOneByName("Categorie " . $i));
            $manager->persist($post);
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
