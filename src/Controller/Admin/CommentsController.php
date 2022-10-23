<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    #[Route('/admin/comments', name: 'app_admin_comments')]
    public function index(Request $request): Response
    {

        $comments = [
            [
                'author' => 'hello',
                'articleTitle' => 'Gopa',
                'content' => 'Starting with version 2.0 this library uses AMQP 0.9.1 by default and thus requires RabbitMQ 2.0 or later version. Usually server upgrades do not require any application code changes since the protocol changes very infrequently but please conduct your own testing before upgrading.',
                'createdAt' => new \DateTime('-1 hour')

            ],
            [
                'author' => 'hello1',
                'articleTitle' => 'Gopa1',
                'content' => 'The package is now maintained by Ramūnas Dronga, Luke Bakken and several VMware engineers working on RabbitMQ.',
                'createdAt' => new \DateTime('-1 days')

            ],
            [
                'author' => 'hello3',
                'articleTitle' => 'Gopa3',
                'content' => 'To not repeat ourselves, if you want to learn more about this library, please refer to the official RabbitMQ tutorials',
                'createdAt' => new \DateTime('-12 days')

            ],

        ];
        $query = $request->get('query');

        if ($query) {
            $comments = array_filter($comments, function ($comment) use ($query) {
                return strripos($comment['content'], $query);
            });
        };
        return $this->render('admin/comments/index.html.twig', [
            'comments' => $comments,
        ]);
    }
}
