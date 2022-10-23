<?php

namespace App\Controller;

use App\Service\SlackService;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage", )
     */

    public function homepage(): Response
    {
        return $this->render('articles/homepage.html.twig', [
            ]

        );
    }

    /**
     * @Route("/articles/{slug}", name="app_articles_show")
     */

    public function show($slug, CacheItemPoolInterface $cache, SlackService $slack): Response
    {
        if ($slug ==='slack'){
            $slack->send('test message');
        }


        $comments = [
            'Starting with version 2.0 this library uses AMQP 0.9.1 by default and thus requires RabbitMQ 2.0 or later version. Usually server upgrades do not require any application code changes since the protocol changes very infrequently but please conduct your own testing before upgrading.',
            'The package is now maintained by Ramūnas Dronga, Luke Bakken and several VMware engineers working on RabbitMQ.',
            'To not repeat ourselves, if you want to learn more about this library, please refer to the official RabbitMQ tutorials'
        ];
        $content = 'A Reselect-generated selector function can be called with as many arguments as you want: selectThings(a, b, c, d, e). However, what matters for re-running the output is not the number of arguments, or whether the arguments themselves have changed to be new references. Instead, it\'s about the "input selectors" that were defined, and whether their results have changed. Similarly, the arguments for the "output selector" are solely based on what the input selectors return.
        Using a series of utilities, you can create this jumbotron, just like the one in
                previous versions of Bootstrap. Check out the examples below for how you can remix and restyle it to
                your liking.
                Yarn is a package manager for your code. It allows you to use and share (e.g. JavaScript) code with other developers from around the world. Yarn does this quickly, securely, and reliably so you don’t ever have to worry.

                Yarn allows you to use other developers’ solutions to different problems, making it easier for you to develop your software. If you have problems, you can report issues or contribute back, and when the problem is fixed, you can use Yarn to keep it all up to date.

                Code is shared through something called a package (sometimes referred to as a module). A package contains all the code being shared as well as a package.json file which describes the package.';

        // кэширование контента статьи
        $articleContent = $cache->get('article_' . md5($content),
            function () use ($content) {
                return $content;
            });

        return $this->render('articles/show.html.twig', [
                'title' => ucwords(str_replace('-', ' ', $slug)),
                'content' => $articleContent,
                'comments' => $comments,

            ]

        );
    }
}