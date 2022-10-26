<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

abstract class BaseFixtures extends Fixture
{
    protected \Faker\Generator $faker;
    protected ObjectManager $manager;


    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create("ru_RU");
        $this->manager = $manager;
        $this->loadData($manager);


    }

    abstract function loadData(ObjectManager $manager);

    protected function create(string $className, callable $factory)
    {

            $entity = new $className();
            $factory($entity);
            $this->manager->persist($entity);
    }

    protected function createMany(string $className, int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $this->create($className,$factory);
        }
        $this->manager->flush();
    }


}
