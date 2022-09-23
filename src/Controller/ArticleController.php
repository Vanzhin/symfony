<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/")
     */

    public function homepage(): Response
    {
        return $this->render('articles/homepage.html.twig', [
            ]

        );
    }

    /**
     * @Route("/articles/{slug}")
     */

    public function show($slug): Response
    {
        $comments = [
            'Starting with version 2.0 this library uses AMQP 0.9.1 by default and thus requires RabbitMQ 2.0 or later version. Usually server upgrades do not require any application code changes since the protocol changes very infrequently but please conduct your own testing before upgrading.',
            'The package is now maintained by Ramūnas Dronga, Luke Bakken and several VMware engineers working on RabbitMQ.',
            'To not repeat ourselves, if you want to learn more about this library, please refer to the official RabbitMQ tutorials'
        ];
        return $this->render('articles/show.html.twig', [
            'title' => ucwords(str_replace('-', ' ', $slug)),
            'comments' => $comments,

            ]

        );
    }
}