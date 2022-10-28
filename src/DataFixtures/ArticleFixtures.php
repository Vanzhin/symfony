<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixtures
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
        $this->createMany(Article::class, 12, function (Article $article) use ($manager){
            $title = $this->faker->words(3, true);
            $article->setTitle($title)
                ->setContent($this->faker->paragraphs($this->faker->numberBetween(2, 5), true))
                ->setPublishedAt(new \DateTime(rand(-10, 0) . ' days'))
                ->setAuthor($this->faker->randomElement(self::$authors))
                ->setLikeCount($this->faker->numberBetween(0, 25));
        });

    }

}
