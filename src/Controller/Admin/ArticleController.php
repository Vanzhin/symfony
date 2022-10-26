<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        return new Response('здесь будет страница создания статьи');
    }
}
