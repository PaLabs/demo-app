<?php


namespace App\DataFixtures\ORM;


use App\Entity\Car;
use App\Entity\Citroen;
use App\Entity\Color;
use App\Entity\Engine;
use App\Entity\Peugeot;
use App\Entity\Renault;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadCarData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $carClasses = [
            Citroen::class,
            Peugeot::class,
            Renault::class
        ];

        for ($i = 0; $i < 100; $i++) {
            $carClass = $carClasses[random_int(0, 2)];
            /** @var Car $entity */
            $entity = new $carClass;

            $color = $this->getReference(LoadColorData::colorId(random_int(0, 99)));
            $engine = $this->getReference(LoadEngineData::engineId(random_int(0, 99)));
            $rescueEngine = $this->getReference(LoadEngineData::engineId(random_int(0, 99)));

            $entity->setName($faker->name)
                ->setCreatedAt($faker->dateTimeThisMonth())
                ->setColor($color)
                ->setEngine($engine)
                ->setRescueEngine($rescueEngine);
            $manager->persist($entity);
        }
        $manager->flush();
    }


    public function getOrder()
    {
        return 4;
    }
}