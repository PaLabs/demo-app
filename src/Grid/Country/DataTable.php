<?php


namespace App\Grid\Country;


use App\Bridge\Grid\Field\HtmlImageField;
use PaLabs\DatagridBundle\DataTable\AbstractConfigurableDataTable;
use PaLabs\DatagridBundle\DataTable\Column\ColumnsBuilder;
use PaLabs\DatagridBundle\DataTable\Column\Type\NumberingColumn;
use PaLabs\DatagridBundle\DataTable\ColumnMakerContext;
use PaLabs\DatagridBundle\DataTable\DataTableSettings;
use PaLabs\DatagridBundle\Field\Renderer\FieldRenderer;
use PaLabs\DatagridBundle\Field\Type\Boolean\BooleanField;
use PaLabs\DatagridBundle\Field\Type\Html\HtmlField;
use PaLabs\DatagridBundle\Field\Type\String\StringField;
use PaLabs\DatagridBundle\Grid\GridParameters;

class DataTable extends AbstractConfigurableDataTable
{
    protected function defaultSettings(GridParameters $parameters): DataTableSettings
    {
        return new DataTableSettings(['image', 'cca2', 'ccn3', 'cca3', 'cioc', 'name.common', 'capital', 'languages', 'area']);
    }


    protected function configureColumns(ColumnsBuilder $builder, GridParameters $parameters)
    {
        $builder->add(new NumberingColumn());
        $callbacks = [
            'image' => function (ColumnMakerContext $context) {
                $item = $context->getRow();
                if (!isset($item['cca2'])) {
                    return StringField::field();
                }
                $url = sprintf('http://flagpedia.net/data/flags/mini/%s.png', strtolower($item['cca2']));
                return HtmlImageField::field($url);
            },
            'cca2' => function (ColumnMakerContext $context) {
                $item = $context->getRow();
                $html = sprintf('<span style="color: red">%s</span>', $item['cca2']);
                return HtmlField::field($html);
            },
            'ccn3' => $this->field('ccn3'),
            'cca3' => $this->field('cca3'),
            'cioc' => $this->field('cioc'),

            'name.common' => function (ColumnMakerContext $context) {
                $item = $context->getRow();
                return StringField::field($item['name']['common']);
            },
            'capital' => function (ColumnMakerContext $context) {
                $value = $context->getRow()['capital'];
                if (is_array($value)) {
                    return StringField::field($value[0]);
                } else {
                    return StringField::field($value);
                }
            },
            'languages' => function (ColumnMakerContext $context) {
                $fields = [];
                foreach ($context->getRow()['languages'] as $id => $language) {
                    $fields[] = StringField::field($language);
                }
                return FieldRenderer::multiValueField($fields, true);
            },
            'area' => $this->field('area'),
            'locked' => function (ColumnMakerContext $context) {
                $item = $context->getRow();
                return BooleanField::field($item['landlocked']);
            },

        ];
        $options = [
            'image' => 'Image',
            'cca2' => 'cca2',
            'ccn3' => 'ccn3',
            'cca3' => 'cca3',
            'cioc' => 'cioc',
            'name.common' => 'Common name',
            'capital' => 'Capital',
            'languages' => 'Languages',
            'area' => 'Area',
            'locked' => 'Locked'
        ];
        $builder->addColumns($callbacks, $options);

    }

    private function field(string $fieldName)
    {
        return function (ColumnMakerContext $context) use ($fieldName) {
            $item = $context->getRow();
            return StringField::field($item[$fieldName]);
        };
    }
}