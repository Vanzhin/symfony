<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Like;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleLikeController extends AbstractController
{
    /**
     * @param Article $article
     * @param LoggerInterface $logger
     * @return Response
     * @Route("/articles/{slug<\S+>}/like", methods={"POST"})
     */

    public function like(Article $article, LoggerInterface $logger, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if ($user) {
            $like = $article->isLikedBy($user);
            if ($like) {
                $article->removeLike($like);
                $logger->info('dislike ' . date("H:i:s"));

            } else {
                $like = new Like();
                $like->setUser($this->getUser());
                $article->addLike($like);
                $logger->info('like ' . date("H:i:s"));

            }
            $em->persist($like);
            $em->flush();
            $count = $article->getLikes()->count();

            return $this->json(['likes' => $count]);

        }
        return $this->json(['likes' => 'noUser']);
    }
}