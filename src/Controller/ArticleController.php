<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController
{
    /**
     * @Route("/")
     */

    public function homepage(): Response
    {
        return new Response('hello everybody!!');
    }

    /**
     * @Route("/articles/{slug}")
     */

    public function show($slug): Response
    {
        return new Response(
            sprintf("Страница статьи: %s.", ucwords(str_replace('-', ' ', $slug)))
        );
    }
}