<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class AppFixtures extends Fixture
{
    /**
     * @var ObjectManager
     */
    private $manager;

    abstract protected function loadData(ObjectManager $entityManager);

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadData($this->manager);
    }

    protected function createMany(ObjectManager $manager, string $className, callable $factory)
    {
        for ($i = 0; $i < 10; ++$i) { // Adjust 10 to the number of entities to create
            $entity = new $className();
            $factory($entity, $i); // Pass the entity and the index to the factory callable
            $manager->persist($entity);
        }
    }
}
