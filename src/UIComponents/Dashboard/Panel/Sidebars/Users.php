<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\UIComponents\Dashboard\Panel\Sidebars;

use Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard\Segments\PanelSidebarInterface;

class Users implements PanelSidebarInterface
{
    public static function getTitle() : string
    {
        return "Users";
    }
}
