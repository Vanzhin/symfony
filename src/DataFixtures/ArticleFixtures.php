<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixtures implements DependentFixtureInterface
{
    private static array $authors = [
        'Nick',
        'Pjotr',
        'Louis',
        'Leo',
        'Hren',
        'Gregory',
        'Julia',
        'David'
    ];

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Article::class, 12, function (Article $article) use ($manager) {
            $title = $this->faker->words(3, true);
            $article->setTitle($title)
                ->setContent($this->faker->paragraphs($this->faker->numberBetween(2, 5), true))
                ->setPublishedAt(new \DateTime(rand(-10, 0) . ' days'))
                ->setAuthor($this->faker->randomElement(self::$authors))
                ->setLikeCount($this->faker->numberBetween(0, 25));

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
            TagFixtures::class
        ];
    }
}
