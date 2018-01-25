<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Material;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadMaterialData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for($i = 0; $i < 100; $i++) {
            $entity = (new Material())
                ->setName($faker->text());
            $manager->persist($entity);

            $this->setReference(self::materialId($i), $entity);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

    public static function materialId($id) {
        return sprintf("%s-%d", Material::class, $id);
    }
}