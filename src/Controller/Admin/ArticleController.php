<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Faker\Factory;

class ArticleController extends AbstractController
{
    #[Route('/admin/article', name: 'app_admin_article')]
    public function index(): Response
    {
        return $this->render('admin/article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route('/admin/article/create', name: 'app_admin_article_create')]
    public function create(EntityManagerInterface $em): Response
    {
        $faker = Factory::create("ru_RU");
        $title = $faker->sentence(3);
        $article = new Article();
        $article->setTitle($title)
            ->setContent($faker->text())
            ->setSlug(str_replace([' ', '.'], ['-', ''], $title))
            ->setCreatedAt(new \DateTimeImmutable(sprintf('-%d days', rand(1, 20))))
            ->setAuthor($faker->name())
            ->setLikeCount(rand(0, 10));

        $em->persist($article);
        $em->flush();

        return new Response(sprintf('сделана запись в бд статьи с id:%d, с названием: %s', $article->getId(), $article->getTitle()));
    }
}
