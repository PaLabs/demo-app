<?php


namespace App\Grid\Engine;


use App\Entity\Engine;
use PaLabs\DatagridBundle\DataSource\Doctrine\Filter\Type\IntegerFilter;
use PaLabs\DatagridBundle\DataSource\Doctrine\Filter\Type\StringFilter;
use PaLabs\DatagridBundle\DataSource\Doctrine\DoctrineDataSource;
use PaLabs\DatagridBundle\DataSource\Filter\FilterBuilder;
use PaLabs\DatagridBundle\DataSource\Order\SortBuilder;
use PaLabs\DatagridBundle\Grid\GridContext;
use PaLabs\DatagridBundle\Grid\GridParameters;

class DataSource extends DoctrineDataSource
{

    protected function configureFilters(FilterBuilder $builder, GridParameters $parameters)
    {
        $builder
            ->add('id', IntegerFilter::class, [
                'label' => 'ID'
            ])
            ->add('name', StringFilter::class, [
                'default' => true,
                'label' => 'Name'
            ])
            ->add('power', StringFilter::class, [
                'default' => true,
                'label' => 'Power'
            ]);
    }

    protected function configureSorting(SortBuilder $builder, GridParameters $parameters)
    {
        $builder
            ->add('entity.id', 'ID')
            ->add('entity.name', 'Name')
            ->add('entity.power', 'Power');
    }

    protected function createQuery(GridContext $context)
    {
        return $this->em->createQueryBuilder()
            ->select('entity')
            ->from(Engine::class, 'entity');
    }
}