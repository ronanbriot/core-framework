<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Events\Ticket;

use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\TicketActivity;

class Delete extends TicketActivity
{
    public static function getId()
    {
        return 'uvdesk.ticket.removed';
    }

    public static function getDescription()
    {
        return "Ticket Deleted";
    }
}
