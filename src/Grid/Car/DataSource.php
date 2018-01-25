<?php


namespace App\Grid\Car;


use App\Bridge\Grid\Filter\Entity\Select2EntityFilter;
use App\Entity\Car;
use App\Entity\Color;
use PaLabs\DatagridBundle\DataSource\DataSourceSettings;
use PaLabs\DatagridBundle\DataSource\Doctrine\DoctrineDataSource;
use PaLabs\DatagridBundle\DataSource\Doctrine\Filter\Type\DateFilter;
use PaLabs\DatagridBundle\DataSource\Doctrine\Filter\Type\StringFilter;
use PaLabs\DatagridBundle\DataSource\Filter\FilterBuilder;
use PaLabs\DatagridBundle\DataSource\Order\OrderItem;
use PaLabs\DatagridBundle\DataSource\Order\SortBuilder;
use PaLabs\DatagridBundle\Grid\GridContext;
use PaLabs\DatagridBundle\Grid\GridParameters;

class DataSource extends DoctrineDataSource
{

    protected function configureFilters(FilterBuilder $builder, GridParameters $parameters)
    {
        $builder
            ->add('name', StringFilter::class, [
                'default' => true,
                'label' => 'Car name'
            ])
            ->add('color', Select2EntityFilter::class, [
                'default' => true,
                'label' => 'Car color',
                'entity_options' => [
                    'class' => Color::class,
                    'choice_label' => 'label'
                ]
            ])
            ->add('createdAt', DateFilter::class, [
                'default' => true,
                'label' => 'Created at'
            ]);
    }

    protected function configureSorting(SortBuilder $builder, GridParameters $parameters)
    {
        $builder
            ->add('entity.id', 'ID')
            ->add('entity.createdAt', 'Created at')
            ->add('color.r', 'Color.Red')
            ->add('color.g', 'Color.Green')
            ->add('color.b', 'Color.Blue');
    }

    protected function createQuery(GridContext $context)
    {
        return $this->em->createQueryBuilder()
            ->select('entity', 'color', 'material')
            ->from(Car::class, 'entity')
            ->leftJoin('entity.color', 'color')
            ->leftJoin('color.material', 'material');
    }

    protected function getDefaultSettings(GridParameters $parameters): DataSourceSettings
    {
        return parent::getDefaultSettings($parameters)
            ->setOrder([new OrderItem('entity.id')]);
    }


}