<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\UIComponents\Dashboard\Panel\Sidebars;

use Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard\Segments\PanelSidebarInterface;

class Reports implements PanelSidebarInterface
{
    public static function getTitle() : string
    {
        return "Reports";
    }
}
