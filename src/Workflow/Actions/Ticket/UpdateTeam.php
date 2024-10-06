<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Actions\Ticket;

use Webkul\Ronanbriot\AutomationBundle\Workflow\FunctionalGroup;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\Ticket;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\SupportTeam;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Action as WorkflowAction;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Event;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\AgentActivity;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\TicketActivity;

class UpdateTeam extends WorkflowAction
{
    public static function getId()
    {
        return 'uvdesk.ticket.assign_team';
    }

    public static function getDescription()
    {
        return "Assign to team";
    }

    public static function getFunctionalGroup()
    {
        return FunctionalGroup::TICKET;
    }

    public static function getOptions(ContainerInterface $container)
    {
        return $container->get('user.service')->getSupportTeams();
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
        
        $subGroup = $entityManager->getRepository(SupportTeam::class)->find($value);

        if ($subGroup) {
            $ticket
                ->setSupportTeam($subGroup)
            ;
            
            $entityManager->persist($ticket);
            $entityManager->flush();
        } else {
            // User Sub Group Not Found. Disable Workflow/Prepared Response
            //$this->disableEvent($event, $entity);
        }
    }
}
