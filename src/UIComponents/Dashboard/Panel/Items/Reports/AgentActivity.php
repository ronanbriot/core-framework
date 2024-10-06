<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\UIComponents\Dashboard\Panel\Items\Reports;

use Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard\Segments\PanelSidebarItemInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\UIComponents\Dashboard\Panel\Sidebars\Reports;

class AgentActivity implements PanelSidebarItemInterface
{
    public static function getTitle() : string
    {
        return "Agent Activity";
    }

    public static function getRouteName() : string
    {
        return 'helpdesk_member_agent_activity';
    }

    public static function getSupportedRoutes() : array
    {
        return [];
    }

    public static function getRoles() : array
    {
        return ['ROLE_AGENT_MANAGE_AGENT_ACTIVITY'];
    }

    public static function getSidebarReferenceId() : string
    {
        return Reports::class;
    }
}
