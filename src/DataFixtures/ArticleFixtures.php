<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Like;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixtures implements DependentFixtureInterface
{

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Article::class, 100, function (Article $article) use ($manager) {
            $title = $this->faker->words(3, true);
            $article->setTitle($title)
                ->setContent($this->faker->paragraphs($this->faker->numberBetween(2, 5), true))
                ->setPublishedAt(new \DateTime(rand(-10, 0) . ' days'))
                ->setAuthor($this->getRandomReferences(User::class))
                ->addLike($this->getRandomReferences(Like::class));
            $tags = [];
            for ($i = 0; $i < $this->faker->numberBetween(0, 5); $i++) {
                $tags[] =$this->getRandomReferences(Tag::class);
        }
            foreach ($tags as $tag){
            $article->addTag($tag);
            }
        });


    }

    public function getDependencies()
    {
        return [
            TagFixtures::class,
            UserFixtures::class,
            LikeFixtures::class
        ];
    }
}
