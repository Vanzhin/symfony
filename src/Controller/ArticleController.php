<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\SlackService;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="app_articles_index", )
     */

    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findPublishedLatest();

        return $this->render('articles/index.html.twig',
            [
                'articles' => $articles
            ]

        );
    }


    /**
     * @Route("/articles/{slug}", name="app_articles_show")
     */

    public function show(Article $article, CacheItemPoolInterface $cache, SlackService $slack): Response
    {
        if ($article->getSlug() === 'slack') {
            $slack->send('test message');
        }

        $comments = [
            'Starting with version 2.0 this library uses AMQP 0.9.1 by default and thus requires RabbitMQ 2.0 or later version. Usually server upgrades do not require any application code changes since the protocol changes very infrequently but please conduct your own testing before upgrading.',
            'The package is now maintained by Ramūnas Dronga, Luke Bakken and several VMware engineers working on RabbitMQ.',
            'To not repeat ourselves, if you want to learn more about this library, please refer to the official RabbitMQ tutorials'
        ];

        // кэширование контента статьи
//        $articleContent = $cache->get('article_' . md5($content),
//            function () use ($content) {
//                return $content;
//            });

        return $this->render('articles/show.html.twig', [
                'article' => $article,
                'comments' => $comments,

            ]

        );
    }
}