<?php


namespace App\Bridge\Grid\Endpoint;


use PaLabs\DatagridBundle\Grid\Export\GridExporterFacade;
use PaLabs\DatagridBundle\Grid\GridOptions;
use PaLabs\DatagridBundle\Grid\View\GridViewBuilder;
use PaLabs\EndpointBundle\EndpointInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class GridEndpoint implements EndpointInterface
{
    protected $templating;
    protected $viewBuilder;
    protected $exporterFacade;

    public function __construct(
        EngineInterface $templating,
        GridViewBuilder $viewBuilder,
        GridExporterFacade $exporterFacade
    )
    {
        $this->templating = $templating;
        $this->viewBuilder = $viewBuilder;
        $this->exporterFacade = $exporterFacade;
    }

    protected abstract function configure() : GridEndpointConfiguration;

    public function execute(Request $request): Response
    {
        $configuration = $this->configure();
        $action = $request->query->get('action', 'view');

        switch ($action) {
            case 'view':
                return $this->displayGrid($request, $configuration);
            case 'export':
                return $this->exportGrid($request, $configuration);
            default:
                throw new NotFoundHttpException(sprintf("Actions %s does not exist", $action));
        }


    }

    private function displayGrid(Request $request, GridEndpointConfiguration $configuration)
    {
        $options = new GridOptions(GridOptions::PAGING_TYPE_SPLIT_BY_PAGES, GridOptions::RENDER_FORMAT_HTML);
        $grid = $this->viewBuilder->buildView(
            $request,
            $configuration->getDataTable(),
            $configuration->getDataSource(),
            $configuration->getGridParameters(),
            $options
        );

        return $this->templating->renderResponse($configuration->getTemplate(), [
            'title' => $configuration->getPageTitle(),
            'grid' => $grid,
            'exportFormats' => $configuration->getExportFormats()
        ]);
    }

    private function exportGrid(Request $request, GridEndpointConfiguration $configuration)
    {
        $format = $request->get('format');

        $options = new GridOptions(GridOptions::PAGING_TYPE_SINGLE_PAGE, $format);
        $grid = $this->viewBuilder->buildView(
            $request,
            $configuration->getDataTable(),
            $configuration->getDataSource(),
            $configuration->getGridParameters(),
            $options
        );
        return $this->exporterFacade->export($grid, $format, $configuration->getExportFileName());
    }
}