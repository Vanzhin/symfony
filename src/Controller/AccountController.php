<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Event\UserEmailChangedEvent;
use App\Event\UserRegistrationEvent;
use App\Form\ArticleFormType;
use App\Form\UserFormType;
use App\Repository\ArticleRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
    public function edit(User $user, Request $request, EntityManagerInterface $em, FileUploader $avatarImageUploader, EventDispatcherInterface $eventDispatcher): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        if ($this->formHandle($form, $request, $em, $avatarImageUploader, $eventDispatcher)) {
            $this->addFlash('profile_flash', 'Профиль обновлен.');
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/create.html.twig', [
            'userForm' => $form->createView(),
            'titleText' => 'Profile update'
        ]);
    }

    private function formHandle(FormInterface            $form,
                                Request                  $request,
                                EntityManagerInterface   $em,
                                FileUploader             $avatarImageUploader,
                                EventDispatcherInterface $eventDispatcher): ?FormInterface
    {
        $oldEmail = $form->getData()->getEmail();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var User $user
             */
            $user = $form->getData();
            $avatar = $form->get('avatar')->getData();
            if ($avatar) {
                $user->setAvatar($avatarImageUploader->uploadImage($avatar, $user->getAvatar()));
            }
            if ($oldEmail !== $user->getEmail()) {
                $eventDispatcher->dispatch(new UserEmailChangedEvent($user));

            }
            $em->persist($user);
            $em->flush();
            return $form;

        }
        return null;
    }
}
