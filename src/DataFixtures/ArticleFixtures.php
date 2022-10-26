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

            $this->addComments($article,10, $manager);
        });

    }
    private function addComments(Article $article, int $count, ObjectManager $manager)
    {
        for ($i=0;$i<rand(0,$count);$i++){
            $comment = (new Comment())
                ->setAuthorName($this->faker->firstName)
                ->setContent($this->faker->paragraph)
                ->setCreatedAt($this->faker->dateTimeBetween)
                ->setArticle($article);
            if ($this->faker->boolean){
                $comment->setDeletedAt($this->faker->dateTimeBetween('-10 days', '-1 days'));
            }
            $manager->persist($comment);
        }
    }

}
