<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleLikeController extends AbstractController
{
    /**
     * @param Article $article
     * @param string $type
     * @param LoggerInterface $logger
     * @return Response
     * @Route("/articles/{slug<\S+>}/like/{type<like|dislike>}", methods={"POST"})
     */

    public function like(Article $article, string $type, LoggerInterface $logger, EntityManagerInterface $em): Response
    {
        if ($type === "like"){
            $like = $article->like();
            $logger->info('like ' . date("H:i:s"));
        } else{
            $like = $article->dislike();
            $logger->info('dislike ' . date("H:i:s"));
        }
        $em->flush();
        return $this->json(['likes' => $like]);
    }
}