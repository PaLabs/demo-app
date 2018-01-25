<?php


namespace App\DataFixtures\ORM;


use App\Entity\Engine;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadEngineData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for($i = 0; $i < 100; $i++) {
            $entity = (new Engine())
                ->setName($faker->text())
                ->setPower(random_int(1, 300));
            $manager->persist($entity);

            $this->setReference(self::engineId($i), $entity);
        }
        $manager->flush();
    }

    public function getOrder()
    {
       return 1;
    }

    public static function engineId($id) {
       return sprintf("%s-%d", Engine::class, $id);
    }
}