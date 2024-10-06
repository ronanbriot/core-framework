<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Actions\Ticket;

use Webkul\Ronanbriot\AutomationBundle\Workflow\FunctionalGroup;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\Ticket;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Action as WorkflowAction;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Event;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\TicketActivity;

class AddNote extends WorkflowAction
{
    public static function getId()
    {
        return 'uvdesk.agent.add_note';
    }

    public static function getDescription()
    {
        return "Add Note";
    }

    public static function getFunctionalGroup()
    {
        return FunctionalGroup::TICKET;
    }

    public static function getOptions(ContainerInterface $container)
    {
        return [];
    }

    public static function applyAction(ContainerInterface $container, Event $event, $value = null)
    {
        if (!$event instanceof TicketActivity) {
            return;
        } else {
            $ticket = $event->getTicket();
            
            if (empty($ticket)) {
                return;
            }
        }

        $params = [
            'ticket' => $ticket, 
            'message' => $value, 
            'source' => 'website', 
            'threadType' => 'note', 
            'createdBy' => 'System', 
        ];

        $container->get('ticket.service')->createThread($ticket, $params);
    }
}
