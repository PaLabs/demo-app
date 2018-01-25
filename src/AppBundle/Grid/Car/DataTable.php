<?php


namespace AppBundle\Grid\Car;


use AppBundle\Entity\Car;
use PaLabs\DatagridBundle\DataSource\DataSourceSettingsForm;
use PaLabs\DatagridBundle\DataSource\Filter\Form\Integer\IntegerFilterData;
use PaLabs\DatagridBundle\DataSource\Filter\Form\Integer\IntegerFilterModelTransformer;
use PaLabs\DatagridBundle\DataTable\AbstractConfigurableDataTable;
use PaLabs\DatagridBundle\DataTable\Column\ColumnsBuilder;
use PaLabs\DatagridBundle\DataTable\Column\Type\NumberingColumn;
use PaLabs\DatagridBundle\DataTable\DataTableSettings;
use PaLabs\DatagridBundle\Field\Type\DateTime\DateTimeField;
use PaLabs\DatagridBundle\Field\Type\String\StringField;
use PaLabs\DatagridBundle\Field\Type\Twig\TwigField;
use PaLabs\DatagridBundle\Field\Type\Url\UrlField;
use PaLabs\DatagridBundle\Grid\GridParameters;
use PaLabs\DatagridBundle\Grid\GridUrlParametersBuilder;
use Symfony\Component\Routing\RouterInterface;

class DataTable extends AbstractConfigurableDataTable
{
    protected $router;

    public function __construct(RouterInterface $router)
    {
        parent::__construct();
        $this->router = $router;
    }

    protected function defaultSettings(GridParameters $parameters): DataTableSettings
    {
        return new DataTableSettings(['id', 'type', 'name', 'color', 'material', 'engine', 'createdAt']);
    }


    protected function configureColumns(ColumnsBuilder $builder, GridParameters $parameters)
    {
        $builder->add('numbering', new NumberingColumn());
        
        $builder->addColumns([
            'id' => function (Car $entity) {
                return StringField::field($entity->getId());
            },
            'type' => function (Car $car) {
                $name = (new \ReflectionClass($car))->getShortName();
                return StringField::field($name);
            },
            'name' => function (Car $entity) {
                return StringField::field($entity->getName());
            },
            'color' => function (Car $entity) {
                return TwigField::field('grid/car/color.html.twig', ['color' => $entity->getColor()]);
            },
            'material' => function (Car $entity) {
                return StringField::field($entity->getColor()->getMaterial()->getName());
            },
            'engine' => function (Car $entity) {
                $urlParams = GridUrlParametersBuilder::build(DataSourceSettingsForm::urlParameters([
                    'id' => IntegerFilterModelTransformer::formData(new IntegerFilterData($entity->getEngine()->getId()))
                ]));
                $url = $this->router->generate('engine_list', $urlParams);
                return UrlField::field($url, sprintf('%s - %s',
                    $entity->getEngine()->getName(), $entity->getEngine()->getPower()));
            },
            'createdAt' => function (Car $entity) {
                return DateTimeField::field($entity->getCreatedAt());
            },
        ], [
            'id' => 'ID',
            'name' => 'Car name',
            'type' => 'Type',
            'color' => 'Color',
            'engine' => 'Engine',
            'material' => 'Material',
            'createdAt' => 'Created at',
        ]);
    }
}