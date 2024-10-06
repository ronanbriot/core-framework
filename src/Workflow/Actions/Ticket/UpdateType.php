<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Actions\Ticket;

use Webkul\Ronanbriot\AutomationBundle\Workflow\FunctionalGroup;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\Ticket;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\TicketType;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Action as WorkflowAction;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Event;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\AgentActivity;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\TicketActivity;

class UpdateType extends WorkflowAction
{
    public static function getId()
    {
        return 'uvdesk.ticket.update_type';
    }

    public static function getDescription()
    {
        return "Set Type As";
    }

    public static function getFunctionalGroup()
    {
        return FunctionalGroup::TICKET;
    }

    public static function getOptions(ContainerInterface $container)
    {
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $collection = $entityManager->getRepository(TicketType::class)->findBy(['isActive' => true], ['code' => 'ASC']);

        return array_map(function ($ticketType) {
            return [
                'id' => $ticketType->getId(),
                'name' => $ticketType->getCode(),
            ];
        }, $collection);
    }

    public static function applyAction(ContainerInterface $container, Event $event, $value = null)
    {
        $entityManager = $container->get('doctrine.orm.entity_manager');

        if (!$event instanceof TicketActivity) {
            return;
        } else {
            $ticket = $event->getTicket();
            $type = $entityManager->getRepository(TicketType::class)->find($value);
            
            if (empty($ticket) || empty($type)) {
                return;
            }
        }
        
        $ticket
            ->setType($type)
        ;

        $entityManager->persist($ticket);
        $entityManager->flush();
    }
}
