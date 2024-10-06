<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Events\Ticket;

use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\TicketActivity;

class Group extends TicketActivity
{
    public static function getId()
    {
        return 'uvdesk.ticket.group_updated';
    }

    public static function getDescription()
    {
        return "Group Updated";
    }
}
