<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard;

use Webkul\Ronanbriot\CoreFrameworkBundle\Framework\ExtendableComponentInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard\Segments\NavigationInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard\Segments\PanelSidebarInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard\Segments\PanelSidebarItemInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard\Segments\HomepageSectionInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard\Segments\HomepageSectionItemInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard\Segments\StylesheetResourceInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard\Segments\JavascriptResourceInterface;

class Dashboard implements ExtendableComponentInterface
{
	public function __construct(DashboardTemplate $dashboardTemplate, NavigationTemplate $navigationTemplate, HomepageTemplate $homepageTemplate)
	{
		$this->homepageTemplate = $homepageTemplate;
		$this->dashboardTemplate = $dashboardTemplate;
		$this->navigationTemplate = $navigationTemplate;
	}

	public function appendNavigation(NavigationInterface $navigation, array $tags = [])
	{
		$this->navigationTemplate->appendNavigation($navigation, $tags);
	}

	public function getNavigationTemplate()
	{
		return $this->navigationTemplate;
	}

	public function appendHomepageSection(HomepageSectionInterface $homepageSection, array $tags = [])
	{
		$this->homepageTemplate->appendSection($homepageSection, $tags);
	}

	public function appendHomepageSectionItem(HomepageSectionItemInterface $homepageSectionItem, array $tags = [])
	{
		$this->homepageTemplate->appendSectionItem($homepageSectionItem, $tags);
	}

	public function getHomepageTemplate()
	{
		return $this->homepageTemplate;
	}

	public function appendStylesheetResource($stylesheet, array $tags = [])
	{
		$this->dashboardTemplate->appendStylesheet($stylesheet, $tags);
	}

	public function appendJavascriptResource($javascript, array $tags = [])
	{
		$this->dashboardTemplate->appendJavascript($javascript, $tags);
	}

	public function getDashboardTemplate()
	{
		return $this->dashboardTemplate;
	}
}
