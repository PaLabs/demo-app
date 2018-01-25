<?php


namespace AppBundle\Grid\Color;


use AppBundle\Bridge\Grid\Endpoint\GridEndpoint;
use AppBundle\Bridge\Grid\Endpoint\GridEndpointConfiguration;
use PaLabs\DatagridBundle\Grid\Export\GridExporterFacade;
use PaLabs\DatagridBundle\Grid\View\GridViewBuilder;
use PaLabs\EndpointBundle\EndpointRoute;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\Route;

class ColorEndpoint extends GridEndpoint
{
    protected $dataTable;
    protected $datasource;

    public function __construct(
        EngineInterface $templating,
        GridViewBuilder $viewBuilder,
        GridExporterFacade $exporterFacade,
        DataTable $dataTable,
        DataSource $dataSource)
    {
        parent::__construct($templating, $viewBuilder, $exporterFacade);
        $this->dataTable = $dataTable;
        $this->datasource = $dataSource;
    }

    protected function configure(): GridEndpointConfiguration
    {
        return GridEndpointConfiguration::fromArray([
            'dataTable' => $this->dataTable,
            'dataSource' => $this->datasource,
            'pageTitle' => 'Colors',
            'exportFileName' => 'colors'
        ]);
    }

    public function routes()
    {
        return new EndpointRoute('colors_list', new Route('/colors/list'));
    }

}
