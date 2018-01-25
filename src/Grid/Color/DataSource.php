<?php


namespace App\Grid\Color;


use App\Entity\Color;
use PaLabs\DatagridBundle\DataSource\Doctrine\DoctrineSinglePageDataSource;
use PaLabs\DatagridBundle\Grid\GridContext;

class DataSource extends DoctrineSinglePageDataSource
{

    protected function createQuery(GridContext $context)
    {
        return $this->em
            ->createQueryBuilder()
            ->select('entity', 'material')
            ->from(Color::class, 'entity')
            ->leftJoin('entity.material', 'material');
    }
}