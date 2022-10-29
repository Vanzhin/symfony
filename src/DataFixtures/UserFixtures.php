<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(User::class, 10, function (User $user) use ($manager) {
            $user
                ->setName($this->faker->firstName())
                ->setEmail($this->faker->email())
                ->setPassword($this->faker->password());
        });
    }
}
