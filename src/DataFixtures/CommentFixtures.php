<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends BaseFixtures implements DependentFixtureInterface
{
    function loadData(ObjectManager $manager)
    {
        $this->createMany(Comment::class, 100, function (Comment $comment) use ($manager) {
            $comment
                ->setAuthorName($this->faker->firstName)
                ->setContent($this->faker->paragraph)
                ->setCreatedAt($this->faker->dateTimeBetween)
                ->setArticle($this->getRandomReferences(Article::class));
            if ($this->faker->boolean) {
                $comment->setDeletedAt($this->faker->dateTimeBetween('-10 days', '-1 days'));
            }
            $manager->persist($comment);

        });
    }

    public function getDependencies(): array
    {
        return [ArticleFixtures::class];
    }
}
