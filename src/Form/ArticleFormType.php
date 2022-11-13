<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ArticleFormType extends AbstractType
{

    private AuthorizationCheckerInterface $checker;
    private UserRepository $userRepository;

    public function __construct(AuthorizationCheckerInterface $checker, UserRepository $userRepository)
    {
        $this->checker = $checker;
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Заголовок',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Содержание ',
                'attr' => ['rows' => 5]

            ])
            ->add('publishedAt', DateTimeType::class, [
                'widget' => 'single_text'
            ])
            ->add('tags', EntityType::class, [
                    'label' => 'Тэг',
                    'class' => Tag::class,
                    'choice_label' => 'title',
                    'multiple' => true
                ]

            );


        if ($this->checker->isGranted('ROLE_ADMIN_ARTICLES')) {
            $builder->add('author', EntityType::class, [
                'label' => 'Автор',
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return "{$user->getName()} (id: {$user->getId()})";
                },
                'choices' => $this->userRepository->findAllSortedByName()
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
