<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Events\Ticket;

use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\TicketActivity;

class Create extends TicketActivity
{
    public static function getId()
    {
        return 'uvdesk.ticket.created';
    }

    public static function getDescription()
    {
        return "Ticket Created";
    }
}
