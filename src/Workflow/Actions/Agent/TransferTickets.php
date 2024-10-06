<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Actions\Agent;

use Webkul\Ronanbriot\AutomationBundle\Workflow\FunctionalGroup;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\Ticket;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\User;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Action as WorkflowAction;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Event;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\AgentActivity;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\CustomerActivity;

class TransferTickets extends WorkflowAction
{
    public static function getId()
    {
        return 'uvdesk.agent.transfer_tickets';
    }

    public static function getDescription()
    {
        return "Transfer Tickets";
    }

    public static function getFunctionalGroup()
    {
        return FunctionalGroup::AGENT;
    }
    
    public static function getOptions(ContainerInterface $container)
    {
        $agentCollection = array_map(function ($agent) {
            return [
                'id' => $agent['id'],
                'name' => $agent['name'],
            ];
        }, $container->get('user.service')->getAgentPartialDataCollection());

        array_unshift($agentCollection, [
            'id' => 'responsePerforming',
            'name' => 'Response Performing Agent',
        ]);

        return $agentCollection;
    }

    public static function applyAction(ContainerInterface $container, Event $event, $value = null)
    {
        $entityManager = $container->get('doctrine.orm.entity_manager');

        if (!$event instanceof AgentActivity) {
            return;
        } else {
            $user = $event->getUser();

            if (empty($user)) {
                return;
            } else {
                if ($value == 'responsePerforming') {
                    $targetUser = $container->get('security.tokenstorage')->getToken()->getUser();
                } else {
                    $targetUser = $entityManager->getRepository(User::class)->find($value);
                }

                if (empty($targetUser) || $targetUser == 'anon.') {
                    return;
                }
            }

        }
        
        $tickets = $entityManager->getRepository(Ticket::class)->getAgentTickets($user->getId(), $container);

        if (!empty($tickets)) {
            foreach ($tickets as $ticket) {
                $ticket
                    ->setAgent($targetUser)
                ;
    
                $entityManager->persist($ticket);
            }
    
            $entityManager->flush();
        }
    }
}
