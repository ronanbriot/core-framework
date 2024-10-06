<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Events\Ticket;

use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\TicketActivity;

class Type extends TicketActivity
{
    public static function getId()
    {
        return 'uvdesk.ticket.type_updated';
    }

    public static function getDescription()
    {
        return "Type Updated";
    }
}
