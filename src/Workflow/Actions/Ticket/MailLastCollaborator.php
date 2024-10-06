<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Actions\Ticket;

use Webkul\Ronanbriot\AutomationBundle\Workflow\FunctionalGroup;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\Ticket;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Action as WorkflowAction;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\EmailTemplates;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Event;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\AgentActivity;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\TicketActivity;

class MailLastCollaborator extends WorkflowAction
{
    public static function getId()
    {
        return 'uvdesk.ticket.mail_last_collaborator';
    }

    public static function getDescription()
    {
        return "Mail to last collaborator";
    }

    public static function getFunctionalGroup()
    {
        return FunctionalGroup::TICKET;
    }

    public static function getOptions(ContainerInterface $container)
    {
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $emailTemplateCollection = array_map(function ($emailTemplate) {
            return [
                'id' => $emailTemplate->getId(),
                'name' => $emailTemplate->getName(),
            ];
        }, $entityManager->getRepository(EmailTemplates::class)->findAll());

        return $emailTemplateCollection;
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
        
        $emailTemplate = $entityManager->getRepository(EmailTemplates::class)->findOneById($value);

        if (count($ticket->getCollaborators()) && $emailTemplate) {
            $mailData = array();
            $createThread = $container->get('ticket.service')->getCreateReply($ticket->getId(),false);
            $mailData['references'] = $createThread['messageId'];
            
            if (!isset($ticket->lastCollaborator)) {
                try {
                    $ticket->lastCollaborator = $ticket->getCollaborators()[ -1 + count($ticket->getCollaborators()) ];
                } catch(\Exception $e) {
                    // Do nothing...
                }
            }

            if ($ticket->lastCollaborator) {
                $placeHolderValues   = $container->get('email.service')->getTicketPlaceholderValues($ticket);
                $subject = $container->get('email.service')->processEmailSubject($emailTemplate->getSubject(),$placeHolderValues);
                $message = $container->get('email.service')->processEmailContent($emailTemplate->getMessage(),$placeHolderValues);
                
                $email = $ticket->lastCollaborator->getEmail();
                $messageId = $container->get('email.service')->sendMail($subject, $message, $email, $mailData);
            }
        }
    }
}
