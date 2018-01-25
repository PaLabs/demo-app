<?php


namespace App\Grid\Color;


use App\Entity\Color;
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
        return new DataTableSettings(['id', 'red', 'green', 'blue', 'material']);
    }


    protected function configureColumns(ColumnsBuilder $builder, GridParameters $parameters)
    {
        $builder->add('numbering', new NumberingColumn());
        $callbacks = [
            'id' => function (Color $entity) {
                return StringField::field($entity->getId());
            },
            'red' => function (Color $entity) {
                return StringField::field($entity->getR());
            },
            'green' => function (Color $entity) {
                return StringField::field($entity->getG());
            },
            'blue' => function (Color $entity) {
                return StringField::field($entity->getB());
            },
            'material' => function (Color $entity) {
                return StringField::field($entity->getMaterial()->getName());
            },
        ];
        $options = [
            'id' => 'ID',
            'red' => 'Red',
            'green' => 'Green',
            'blue' => 'Blue',
            'material' => 'Material',
        ];
        $builder->addColumns($callbacks, $options);

    }
}