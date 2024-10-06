<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Events\Ticket;

use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\TicketActivity;

class Status extends TicketActivity
{
    public static function getId()
    {
        return 'uvdesk.ticket.status_updated';
    }

    public static function getDescription()
    {
        return "Status Updated";
    }
}
