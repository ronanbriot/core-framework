<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Framework\ExtendableComponentInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard\Segments\StylesheetResourceInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard\Segments\JavascriptResourceInterface;

class DashboardTemplate implements ExtendableComponentInterface
{
    private $scripts = [];
    private $stylesheets = [];

	public function __construct(ContainerInterface $container, RequestStack $requestStack, RouterInterface $router)
	{
		$this->router = $router;
		$this->container = $container;
		$this->requestStack = $requestStack;
    }
    
    public function appendJavascript($javascript, $tags = [])
	{
		$this->scripts[] = $javascript;
    }

    public function getJavascriptResources()
    {
        return $this->scripts;
    }

	public function appendStylesheet($stylesheet, $tags = [])
	{
		$this->stylesheets[] = $stylesheet;
    }

    public function getStylesheetResources()
    {
        return $this->stylesheets;
    }
}
