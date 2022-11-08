<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixtures
{


    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function loadData(ObjectManager $manager): void
    {
        $this->create(User::class, function (User $user) use ($manager) {
            $user
                ->setName('admin')
                ->setEmail('admin@admin.ru')
                ->setPassword($this->hasher->hashPassword($user, '123456789'))
            ->setRoles(['ROLE_ADMIN']);
        });

        $this->createMany(User::class, 10, function (User $user) use ($manager) {
            $user
                ->setName($this->faker->firstName())
                ->setEmail($this->faker->email())
                ->setPassword($this->hasher->hashPassword($user, '123456789'));
        });
    }
}
