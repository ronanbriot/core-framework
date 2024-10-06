<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Events\Ticket;

use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\TicketActivity;

class ThreadUpdate extends TicketActivity
{
    public static function getId()
    {
        return 'uvdesk.ticket.thread_updated';
    }

    public static function getDescription()
    {
        return "Thread Updated";
    }
}
