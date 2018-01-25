<?php


namespace App\Grid\Engine;


use App\Entity\Engine;
use PaLabs\DatagridBundle\DataTable\AbstractConfigurableDataTable;
use PaLabs\DatagridBundle\DataTable\Column\ColumnsBuilder;
use PaLabs\DatagridBundle\DataTable\Column\Type\NumberingColumn;
use PaLabs\DatagridBundle\DataTable\DataTableSettings;
use PaLabs\DatagridBundle\Field\Type\String\StringField;
use PaLabs\DatagridBundle\Grid\GridParameters;

class DataTable extends AbstractConfigurableDataTable
{
    protected function defaultSettings(GridParameters $parameters): DataTableSettings
    {
        return new DataTableSettings(['id', 'name', 'power']);
    }


    protected function configureColumns(ColumnsBuilder $builder, GridParameters $parameters)
    {
        $builder->add('numbering', new NumberingColumn());
        $callbacks = [
            'id' => function (Engine $entity) {
                return StringField::field($entity->getId());
            },
           'name' => function (Engine $entity) {
                return StringField::field($entity->getName());
            },
            'power' => function (Engine $entity) {
                return StringField::field($entity->getPower());
            },
        ];
        $options = [
            'id' => 'ID',
            'name' => 'Name',
            'power' => 'Power',
        ];
        $builder->addColumns($callbacks, $options);

    }
}