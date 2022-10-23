<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    /**
     * @param int $id
     * @param string $type
     * @param LoggerInterface $logger
     * @return Response
     * @Route("/articles/{id<\d+>}/like/{type<like|dislike>}", methods={"POST"})
     */

    public function like(int $id, string $type, LoggerInterface $logger): Response
    {
        if ($type === "like"){
            $like = rand(50,100);
            $logger->info('like ' . date("H:i:s"));
        } else{
            $like = rand(1,49);
            $logger->info('dislike ' . date("H:i:s"));

        }
        return $this->json(['likes' => $like]);
    }
}