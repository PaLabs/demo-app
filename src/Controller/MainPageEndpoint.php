<?php

namespace App\Controller;

use PaLabs\EndpointBundle\EndpointInterface;
use PaLabs\EndpointBundle\EndpointRoute;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;

class MainPageEndpoint implements EndpointInterface
{
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function routes()
    {
        return new EndpointRoute('main_page', new Route('/'));
    }


    public function execute(Request $request): Response
    {
        return $this->templating->renderResponse('main.html.twig', []);
    }
}
