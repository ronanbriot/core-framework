<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Actions\Agent;

use Webkul\Ronanbriot\CoreFrameworkBundle\Entity as CoreEntities;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\Ticket;
use Webkul\Ronanbriot\AutomationBundle\Workflow\FunctionalGroup;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Action as WorkflowAction;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\EmailTemplates;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Event;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\AgentActivity;
use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\TicketActivity;

class MailAgent extends WorkflowAction
{
    public static function getId()
    {
        return 'uvdesk.agent.mail_agent';
    }

    public static function getDescription()
    {
        return "Mail to agent";
    }

    public static function getFunctionalGroup()
    {
        return FunctionalGroup::AGENT;
    }
    
    public static function getOptions(ContainerInterface $container)
    {
        $entityManager = $container->get('doctrine.orm.entity_manager');

        return array_map(function ($emailTemplate) {
            return [
                'id' => $emailTemplate->getId(),
                'name' => $emailTemplate->getName(),
            ];
        }, $entityManager->getRepository(EmailTemplates::class)->findAll());
    }

    public static function applyAction(ContainerInterface $container, Event $event, $value = null)
    {
        $entityManager = $container->get('doctrine.orm.entity_manager');

        switch (true) {
            // Agent created
            case $event instanceof AgentActivity:
                $user = $event->getUser();
                $emailTemplate = $entityManager->getRepository(EmailTemplates::class)->findOneById($value);

                if (empty($user) || empty($emailTemplate)) {
                    // @TODO: Send default email template
                    return;
                }

                $emailPlaceholders = $container->get('email.service')->getEmailPlaceholderValues($user, 'agent');
                $subject = $container->get('email.service')->processEmailSubject($emailTemplate->getSubject(), $emailPlaceholders);
                $message = $container->get('email.service')->processEmailContent($emailTemplate->getMessage(), $emailPlaceholders);
                
                $messageId = $container->get('email.service')->sendMail($subject, $message, $user->getEmail(), []);
                
                break;
            // Ticket created
            case $event instanceof TicketActivity:
                $ticket = $event->getTicket();
                $emailTemplate = $entityManager->getRepository(EmailTemplates::class)->findOneById($value);

                if (empty($emailTemplate)) {
                    break;
                }

                $ticketPlaceholders = $container->get('email.service')->getTicketPlaceholderValues($ticket);
                $subject = $container->get('email.service')->processEmailSubject($emailTemplate->getSubject(), $ticketPlaceholders);
                $message = $container->get('email.service')->processEmailContent($emailTemplate->getMessage(), $ticketPlaceholders);

                $messageId = $container->get('email.service')->sendMail($subject, $message, $ticket->getCustomer()->getEmail(), [
                    'In-Reply-To' => $ticket->getUniqueReplyTo(),
                    'References' => $ticket->getReferenceIds(),
                ]);

                if (!empty($messageId)) {
                    $thread = $ticket->createdThread;
                    $thread->setMessageId($messageId);

                    $entityManager->persist($thread);
                    $entityManager->flush();
                }

                $emailTemplate = $container->get('email.service')->getEmailTemplate($action['value']['value'], $ticket->getCompany()->getId());

                break;
            default:
                break;
        }
    }
}
