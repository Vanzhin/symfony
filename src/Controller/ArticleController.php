<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use App\Service\FileUploader;
use App\Service\SlackService;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormInterface;
use \Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="app_articles_index", )
     */

    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $articles = $articleRepository->findPublishedLatestQuery();
        $pagination = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        return $this->render('articles/index.html.twig',
            [
                'pagination' => $pagination
            ]

        );
    }


    /**
     * @Route("/articles/{slug}", name="app_articles_show")
     */

    public function show(Article $article, CacheItemPoolInterface $cache, SlackService $slack): Response
    {
        if ($article->getSlug() === 'slack') {
            $slack->send('test message');
        }

        // кэширование контента статьи
//        $articleContent = $cache->get('article_' . md5($content),
//            function () use ($content) {
//                return $content;
//            });

        return $this->render('articles/show.html.twig', [
                'article' => $article,
            ]

        );
    }

    #[Route("/article/{id}/edit", name: 'app_article_edit')]
    #[IsGranted('MANAGE_ARTICLE', 'article')]
    public function edit(Article $article, Request $request, EntityManagerInterface $em, FileUploader $articleImageUploader): Response
    {
        $form = $this->createForm(ArticleFormType::class, $article);
        if ($this->formHandle($form, $request, $em, $articleImageUploader)) {
            $this->addFlash('article_flash', 'Статья обновлена.');
            return $this->redirectToRoute('app_articles_index');
        }

        return $this->render('articles/create.html.twig', [
            'articleForm' => $form->createView(),
            'buttonText' => 'Обновить',
            'titleText' => 'Обновление статьи'
        ]);
    }

    /**
     * @throws FilesystemException
     */
    #[Route("/article/{id}/delete", name: 'app_article_delete')]
    #[IsGranted('MANAGE_ARTICLE', 'article')]
    public function delete(Article $article, EntityManagerInterface $em, FileUploader $articleImageUploader): Response
    {

        $em->remove($article);
        $articleImageUploader->delete($article->getImage());
        $em->flush();
        $this->addFlash('article_flash', 'Статья удалена.');
        return $this->redirectToRoute('app_articles_index');

    }

    #[Route("/article/create", name: 'app_article_create')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function create(Request $request, EntityManagerInterface $em, FileUploader $articleImageUploader): Response
    {
        $form = $this->createForm(ArticleFormType::class, new Article());
        if ($this->formHandle($form, $request, $em, $articleImageUploader)) {
            $this->addFlash('article_flash', 'Статья создана.');
            return $this->redirectToRoute('app_articles_index');
        }

        return $this->render('articles/create.html.twig', [
            'articleForm' => $form->createView(),
            'buttonText' => 'Создать',
            'titleText' => 'Создание статьи'
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