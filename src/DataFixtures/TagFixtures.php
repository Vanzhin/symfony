<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends BaseFixtures
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Tag::class, 15, function (Tag $tag) use ($manager){
            $tag->setTitle($this->faker->realText(10));
        });
    }
}
