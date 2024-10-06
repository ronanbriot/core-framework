<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Events\Ticket;

use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\TicketActivity;

class Priority extends TicketActivity
{
    public static function getId()
    {
        return 'uvdesk.ticket.priority_updated';
    }

    public static function getDescription()
    {
        return "Priority Updated";
    }
}
