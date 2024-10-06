<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\UIComponents\Ticket\QuickActionButtons;

use Twig\Environment as TwigEnvironment;
use Webkul\Ronanbriot\CoreFrameworkBundle\Dashboard\DashboardTemplate;
use Webkul\Ronanbriot\CoreFrameworkBundle\Tickets\QuickActionButtonInterface;

class SavedReplies implements QuickActionButtonInterface
{
    public static function getRoles() : array
    {
        return [];
    }

    public function renderTemplate(TwigEnvironment $twig)
    {
        return $twig->render('@UVDeskCoreFramework/tickets/quick-actions/saved-replies.html.twig');
    }

    public function prepareDashboard(DashboardTemplate $dashboard)
    {
        $dashboard->appendJavascript('bundles/uvdeskcoreframework/js/tickets/quick-actions/saved-replies.js');
    }
}
