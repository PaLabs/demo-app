<?php


namespace App\Grid\Github;


use PaLabs\DatagridBundle\DataSource\AbstractConfigurableDataSource;
use PaLabs\DatagridBundle\DataSource\DataSourceConfiguration;
use PaLabs\DatagridBundle\DataSource\DataSourceSettings;
use PaLabs\DatagridBundle\DataSource\Filter\FilterBuilder;
use PaLabs\DatagridBundle\DataSource\Filter\Form\String\StringFilterForm;
use PaLabs\DatagridBundle\DataSource\Order\OrderItem;
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

    protected function configureFilters(FilterBuilder $builder, GridParameters $parameters)
    {
        $builder
            ->addForm('term', StringFilterForm::class, [
                'default' => true,
                'label' => 'Search term'
            ]);
    }

    protected function configureSorting(SortBuilder $builder, GridParameters $parameters)
    {
        $builder
            ->add('stars', 'Stars')
            ->add('forks', 'Forks')
            ->add('updated', 'Updated');
    }

    public function rows(DataSourceConfiguration $configuration, GridContext $context): DataSourceResultContainer
    {
        $filters = $context->getDataSourceSettings()->getFilters();
        if (!isset($filters['term'])) {
            return $this->emptyData();
        }

        $parameters = [
            'q' => $filters['term']->getValue(),
            'page' => $context->getDataSourceSettings()->getPage(),
            'per_page' => $context->getDataSourceSettings()->getPerPage()
        ];

        $order = $context->getDataSourceSettings()->getOrder();
        if (count($order) > 0) {
            /** @var OrderItem $orderItem */
            $orderItem = $order[0];
            $parameters['sort'] = $orderItem->getField();
            $parameters['order'] = $orderItem->getDirection();
        }

        switch ($context->getOptions()->getPagingType()) {
            case GridOptions::PAGING_TYPE_SPLIT_BY_PAGES:
                return $this->singlePageRows($parameters, $configuration, $context);
            case GridOptions::PAGING_TYPE_SINGLE_PAGE:
                // return $this->allPagesRows($parameters, $configuration, $context);
            default:
                throw new \Exception(sprintf("Unsupported context type: %s", $context->getOptions()->getPagingType()));
        }

    }

    protected function singlePageRows($parameters, DataSourceConfiguration $configuration, GridContext $context)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://api.github.com/search/repositories', ['query' => $parameters]);
        $data = \GuzzleHttp\json_decode($response->getBody(), true);
        $totalCount = $data['total_count'];

        $page = new DataSourcePage($data['items'], $data);
        $pager = new Pager($context->getDataSourceSettings()->getPage(), $context->getDataSourceSettings()->getPerPage(), $totalCount);
        $result = new PagedDataSourceResultContainer([$page], $pager);
        return $result;
    }

    private function emptyData()
    {
        $page = new DataSourcePage([], []);
        $result = new DataSourceResultContainer([$page], 0);
        return $result;
    }
}