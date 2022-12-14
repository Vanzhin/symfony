<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailViewController extends AbstractController
{
    #[Route('/email/view', name: 'app_email_view')]
    public function index(): Response
    {
        return $this->render('emails/welcome.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
