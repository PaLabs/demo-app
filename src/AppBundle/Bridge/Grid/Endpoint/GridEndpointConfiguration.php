<?php


namespace AppBundle\Bridge\Grid\Endpoint;


use PaLabs\DatagridBundle\DataSource\ConfigurableDataSource;
use PaLabs\DatagridBundle\DataTable\ConfigurableDataTable;
use PaLabs\DatagridBundle\Grid\ConfigurableGridInterface;
use PaLabs\DatagridBundle\Grid\Export\XlsxExporter;
use PaLabs\DatagridBundle\Grid\GridParameters;

class GridEndpointConfiguration
{
    protected $dataTable;
    protected $dataSource;
    protected $gridParameters;
    protected $template;
    protected $pageTitle;
    protected $exportFileName;
    protected $exportFormats;

    public static function fromArray(array $parameters) {
        $description = new static();
        $description->dataTable = $parameters['dataTable'];
        $description->dataSource = $parameters['dataSource'];
        $description->gridParameters = $parameters['gridParameters'] ?? new GridParameters();

        $description->pageTitle = $parameters['pageTitle'];
        $description->template = $parameters['template'] ?? 'grid/layout/list.html.twig';
        $description->exportFileName = $parameters['exportFileName'] ?? 'grid_export';
        $description->exportFormats = $parameters['exportFormat'] ?? ['Excel' => XlsxExporter::FORMAT];
        return $description;
    }

    public function getDataTable() : ConfigurableDataTable
    {
        return $this->dataTable;
    }

    public function getGridParameters() : GridParameters
    {
        return $this->gridParameters;
    }

    public function getDataSource() : ConfigurableDataSource
    {
        return $this->dataSource;
    }

    public function getPageTitle() : string
    {
        return $this->pageTitle;
    }

    public function getTemplate() : string
    {
        return $this->template;
    }

    public function getExportFileName(): string
    {
        return $this->exportFileName;
    }

    public function getExportFormats(): array
    {
        return $this->exportFormats;
    }




}