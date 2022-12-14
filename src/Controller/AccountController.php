<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleFormType;
use App\Form\UserFormType;
use App\Repository\ArticleRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[IsGranted('ROLE_USER')]

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $articles = $articleRepository->findLatestByUserQuery($this->getUser());
        $articlePagination = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/,
            ['page_name' => 'article_page']
        );

        return $this->render('account/index.html.twig', [
            'articlePagination' => $articlePagination,
        ]);
    }
    #[Route("/account/{id}/edit", name: 'app_account_edit')]
    public function edit(User $user, Request $request, EntityManagerInterface $em, FileUploader $articleImageUploader): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
//        if ($this->formHandle($form, $request, $em, $articleImageUploader)) {
//            $this->addFlash('article_flash', 'Статья обновлена.');
//            return $this->redirectToRoute('app_articles_index');
//        }

        return $this->render('account/create.html.twig', [
            'userForm' => $form->createView(),
            'titleText' => 'Profile update'
        ]);
    }

    private function formHandle(FormInterface $form, Request $request, EntityManagerInterface $em, FileUploader $articleImageUploader): ?FormInterface
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Article $article
             */
            $article = $form->getData();
            $image = $form->get('image')->getData();
            if ($image){
                $article->setImage($articleImageUploader->uploadImage($image, $article->getImage()));
            }

            if (!$this->isGranted('ROLE_ADMIN_ARTICLES')) {
                $article
                    ->setAuthor($this->getUser());
            }

            $em->persist($article);
            $em->flush();
            return $form;

        }
        return null;
    }
}
