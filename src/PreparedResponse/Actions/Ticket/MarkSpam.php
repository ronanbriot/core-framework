<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\PreparedResponse\Actions\Ticket;

use Webkul\Ronanbriot\AutomationBundle\PreparedResponse\FunctionalGroup;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\Ticket;
use Webkul\Ronanbriot\AutomationBundle\PreparedResponse\Action as PreparedResponseAction;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\TicketStatus;

class MarkSpam extends PreparedResponseAction
{
    public static function getId()
    {
        return 'uvdesk.ticket.mark_spam';
    }

    public static function getDescription()
    {
        return "Mark Spam";
    }

    public static function getFunctionalGroup()
    {
        return FunctionalGroup::TICKET;
    }

    public static function getOptions(ContainerInterface $container)
    {
        return [];
    }

    public static function applyAction(ContainerInterface $container, $entity, $value = null)
    {
        $entityManager = $container->get('doctrine.orm.entity_manager');
        if($entity instanceof Ticket) {
            $status = $entityManager->getRepository(TicketStatus::class)->find(6);
            $entity->setStatus($status);
            $entityManager->persist($entity);
            $entityManager->flush();
        }
    }
}
