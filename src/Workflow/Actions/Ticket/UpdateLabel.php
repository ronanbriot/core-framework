<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Actions\Ticket;

use Webkul\Ronanbriot\AutomationBundle\Workflow\FunctionalGroup;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\Ticket;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\SupportLabel;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Action as WorkflowAction;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Event;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\AgentActivity;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\TicketActivity;

class UpdateLabel extends WorkflowAction
{
    public static function getId()
    {
        return 'uvdesk.ticket.update_label';
    }

    public static function getDescription()
    {
        return "Set Label As";
    }

    public static function getFunctionalGroup()
    {
        return FunctionalGroup::TICKET;
    }

    public static function getOptions(ContainerInterface $container)
    {
        $entityManager = $container->get('doctrine.orm.entity_manager');

        return array_map(function ($label) {
            return [
                'id' => $label->getId(),
                'name' => $label->getName(),
            ];
        }, $container->get('ticket.service')->getUserLabels());
    }

    public static function applyAction(ContainerInterface $container, Event $event, $value = null)
    {
        $entityManager = $container->get('doctrine.orm.entity_manager');

        if (!$event instanceof TicketActivity) {
            return;
        } else {
            $ticket = $event->getTicket();
            
            if (empty($ticket)) {
                return;
            }
        }
        
        $isAlreadyAdded = 0;
        $labels = $container->get('ticket.service')->getTicketLabelsAll($ticket->getId());

        if (is_array($labels)) {
            foreach ($labels as $label) {
                if ($label['id'] == $value) {
                    $isAlreadyAdded = 1;
                }
            }
        }

        if (!$isAlreadyAdded) {
            $label = $entityManager->getRepository(SupportLabel::class)->find($value);

            if ($label) {
                $ticket
                    ->addSupportLabel($label)
                ;

                $entityManager->persist($ticket);
                $entityManager->flush();
            }
        }
    }
}
