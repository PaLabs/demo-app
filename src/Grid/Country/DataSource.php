<?php


namespace App\Grid\Country;


use App\Bridge\Grid\DataSource\ArraySortApplier;
use App\Bridge\Grid\Filter\Linq\LinqBooleanFilter;
use App\Bridge\Grid\Filter\Linq\LinqFilterApplier;
use App\Bridge\Grid\Filter\Linq\LinqStringFilter;
use PaLabs\DatagridBundle\DataSource\AbstractConfigurableDataSource;
use PaLabs\DatagridBundle\DataSource\DataSourceConfiguration;
use PaLabs\DatagridBundle\DataSource\Filter\FilterBuilder;
use PaLabs\DatagridBundle\DataSource\Order\SortBuilder;
use PaLabs\DatagridBundle\DataSource\Result\DataSourcePage;
use PaLabs\DatagridBundle\DataSource\Result\DataSourceResultContainer;
use PaLabs\DatagridBundle\DataSource\Result\PagedDataSourceResultContainer;
use PaLabs\DatagridBundle\DataSource\Result\Pager;
use PaLabs\DatagridBundle\Grid\GridContext;
use PaLabs\DatagridBundle\Grid\GridOptions;
use PaLabs\DatagridBundle\Grid\GridParameters;

class DataSource extends AbstractConfigurableDataSource
{
    protected function configureSorting(SortBuilder $builder, GridParameters $parameters)
    {
        $builder
            ->add('cca2', 'cca2', [
                'type' => ArraySortApplier::class,
                'comparator' => function (array $a, array $b) {
                    return strcmp($b['cca2'], $a['cca2']);
                },
            ])
            ->add('area', 'Area', [
                'type' => ArraySortApplier::class,
                'comparator' => function (array $a, array $b) {
                    return $b['area'] - $a['area'];
                },
            ])
            ->add('name.common', 'Common name', [
                'type' => ArraySortApplier::class,
                'comparator' => function (array $a, array $b) {
                    return strcmp($a['name']['common'], $b['name']['common']);
                }
            ]);
    }

    protected function configureFilters(FilterBuilder $builder, GridParameters $parameters)
    {
        $builder
            ->add('name_common', LinqStringFilter::class, [
                'default' => true,
                'label' => 'Common name'
            ], null, ['selector' => function ($item) {
                return $item['name']['common'];
            }])
            ->add('capital', LinqStringFilter::class, [
                'default' => true,
                'label' => 'Capital'
            ], null, ['selector' => function ($item) {
                return $item['capital'];
            }])
            ->add('locked', LinqBooleanFilter::class, [
                'label' => 'Locked'
            ], null, ['selector' => function ($item) {
                return $item['landlocked'];
            }])
        ;
    }

    public function rows(DataSourceConfiguration $configuration, GridContext $context): DataSourceResultContainer
    {
        $data = \GuzzleHttp\json_decode(file_get_contents(__DIR__ . '/../../../../vendor/mledoze/countries/countries.json'), true);
        $sortApplier = new ArraySortApplier();
        $sortApplier->apply($data, $context->getDataSourceSettings()->getOrder(), $configuration->getSorting());

        switch ($context->getOptions()->getPagingType()) {
            case GridOptions::PAGING_TYPE_SPLIT_BY_PAGES:
                return $this->onePageRows($data, $configuration, $context);
            case GridOptions::PAGING_TYPE_SINGLE_PAGE:
                return $this->allPagesRows($data, $configuration, $context);
            default:
                throw new \Exception(sprintf("Unsupported context type: %s", $context->getOptions()->getPagingType()));
        }
    }

    private function onePageRows(array $data, DataSourceConfiguration $configuration, GridContext $context)
    {
        $linq = from($data);
        $filterApplier = new LinqFilterApplier();
        $linq = $filterApplier->apply($linq, $configuration->getFilters(), $context->getDataSourceSettings()->getFilters());
        $preparedData = $linq->toArrayDeep();

        $settings = $context->getDataSourceSettings();
        $startOffset = $settings->getPerPage() * ($settings->getPage() - 1);
        $slice = array_slice($preparedData, $startOffset, $settings->getPerPage());

        $page = new DataSourcePage($slice, []);
        $pager = new Pager($settings->getPage(), $settings->getPerPage(), count($preparedData));
        $result = new PagedDataSourceResultContainer([$page], $pager);
        return $result;
    }

    private function allPagesRows(array $data, DataSourceConfiguration $configuration, GridContext $context)
    {
        $linq = from($data);
        $filterApplier = new LinqFilterApplier();
        $linq = $filterApplier->apply($linq, $configuration->getFilters(), $context->getDataSourceSettings()->getFilters());
        $preparedData = $linq->toArrayDeep();

        $page = new DataSourcePage($preparedData, []);
        $result = new DataSourceResultContainer([$page], count($preparedData));
        return $result;
    }


}