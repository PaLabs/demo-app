<?php


namespace App\DataFixtures\ORM;


use App\Entity\Color;
use App\Entity\Material;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadColorData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for($i = 0; $i < 100; $i++) {
            $rgbColor = $faker->rgbColorAsArray;
            $material = $this->getReference(LoadMaterialData::materialId(random_int(0, 99)));

            $entity = (new Color())
                ->setMaterial($material)
                ->setR($rgbColor[0])
                ->setG($rgbColor[1])
                ->setB($rgbColor[2])
                ->setEnabled($faker->boolean());
            $manager->persist($entity);

            $this->setReference(self::colorId($i), $entity);
        }
        $manager->flush();
    }

    public function getOrder()
    {
       return 3;
    }

    public static function colorId($id) {
        return sprintf("%s-%d", Color::class, $id);
    }
}