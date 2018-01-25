<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Car;
use AppBundle\Entity\Citroen;
use AppBundle\Entity\Color;
use AppBundle\Entity\Engine;
use AppBundle\Entity\Peugeot;
use AppBundle\Entity\Renault;
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