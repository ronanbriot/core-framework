<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Actions\Ticket;

use Webkul\Ronanbriot\AutomationBundle\Workflow\FunctionalGroup;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\Ticket;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\Tag;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Action as WorkflowAction;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Event;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\AgentActivity;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\TicketActivity;

class UpdateTag extends WorkflowAction
{
    public static function getId()
    {
        return 'uvdesk.ticket.update_tag';
    }

    public static function getDescription()
    {
        return "Set Tag As";
    }

    public static function getFunctionalGroup()
    {
        return FunctionalGroup::TICKET;
    }

    public static function getOptions(ContainerInterface $container)
    {
        $entityManager = $container->get('doctrine.orm.entity_manager');

        return array_map(function ($tag) {
            return [
                'id' => $tag->getId(),
                'name' => $tag->getName(),
            ];
        }, $entityManager->getRepository(Tag::class)->findAll());
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
        $tags = $container->get('ticket.service')->getTicketTagsById($ticket->getId());

        if (is_array($tags)) {
            foreach ($tags as $tag) {
                if ($tag['id'] == $value) {
                    $isAlreadyAdded = 1;
                }
            }
        }

        if (!$isAlreadyAdded) {
            $tag = $entityManager->getRepository(Tag::class)->find($value);

            if ($tag) {
                $ticket
                    ->addSupportTag($tag)
                ;

                $entityManager->persist($ticket);
                $entityManager->flush();
            } else {
                // Ticket Tag Not Found. Disable Workflow/Prepared Response
                //$this->disableEvent($event, $object);
            }
        }
    }
}
