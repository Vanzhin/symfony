<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    /**
     * @param int $id
     * @param string $type
     * @Route("/articles/{id<\d+>}/like/{type<like|dislike>}", methods={"POST"})
     */

    public function like(int $id, string $type): Response
    {
        if ($type === "like"){
            $like = rand(50,100);
        } else{
            $like = rand(1,49);
        }
        return $this->json(['likes' => $like]);
    }
}