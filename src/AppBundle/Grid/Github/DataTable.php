<?php


namespace AppBundle\Grid\Github;


use PaLabs\DatagridBundle\DataTable\AbstractConfigurableDataTable;
use PaLabs\DatagridBundle\DataTable\Column\ColumnsBuilder;
use PaLabs\DatagridBundle\DataTable\Column\Type\NumberingColumn;
use PaLabs\DatagridBundle\DataTable\ColumnMakerContext;
use PaLabs\DatagridBundle\DataTable\DataTableSettings;
use PaLabs\DatagridBundle\Field\Type\Boolean\BooleanField;
use PaLabs\DatagridBundle\Field\Type\DateTime\DateTimeField;
use PaLabs\DatagridBundle\Field\Type\String\StringField;
use PaLabs\DatagridBundle\Field\Type\Url\UrlField;
use PaLabs\DatagridBundle\Grid\GridParameters;

class DataTable extends AbstractConfigurableDataTable
{
    protected function defaultSettings(GridParameters $parameters): DataTableSettings
    {
        return new DataTableSettings(['id', 'full_name', 'description', 'fork', 'created_at', 'stars', 'language']);
    }


    protected function configureColumns(ColumnsBuilder $builder, GridParameters $parameters)
    {
        $builder->add('numbering', new NumberingColumn());

        $builder->addColumns([
            'id' => function (ColumnMakerContext $context) {
                $item = $context->getRow();
                return StringField::field($item['id']);
            },
            'full_name' => function (ColumnMakerContext $context) {
                $item = $context->getRow();
                return UrlField::field($item['html_url'], $item['full_name'], ['target' => 'blank']);
            },
            'description' => function (ColumnMakerContext $context) {
                $item = $context->getRow();
                return StringField::field($item['description']);
            },
            'fork' => function (ColumnMakerContext $context) {
                $item = $context->getRow();
                return BooleanField::field((bool)$item['fork']);
            },
            'created_at' => function (ColumnMakerContext $context) {
                $item = $context->getRow();
                $date = new \DateTime($item['created_at']);
                return DateTimeField::field($date);
            },
            'stars' => function (ColumnMakerContext $context) {
                $item = $context->getRow();
                return StringField::field($item['stargazers_count']);
            },
            'language' => function (ColumnMakerContext $context) {
                $item = $context->getRow();
                return StringField::field($item['language']);
            },

        ], [
            'id' => 'ID',
            'full_name' => 'Full name',
            'description' => 'Description',
            'fork' => 'Fork',
            'created_at' => 'Created at',
            'stars' => 'Stars',
            'language' => 'Language'
        ]);
    }
}