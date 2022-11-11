<?php

namespace App\DataFixtures;

use App\Entity\Like;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LikeFixtures extends BaseFixtures
{
    function loadData(ObjectManager $manager)
    {
        $this->createMany(Like::class, 10, function (Like $like) use ($manager){
            $like->setUser($this->getRandomReferences(User::class));
        });
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
