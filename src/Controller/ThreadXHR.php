<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Webkul\Ronanbriot\CoreFrameworkBundle\Services\TicketService;
use Webkul\Ronanbriot\CoreFrameworkBundle\Entity\Ticket;

class ThreadXHR extends AbstractController
{
    private $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function listTicketThreadCollectionXHR($ticketId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $request = $this->container->get('request_stack')->getCurrentRequest();

        if (true === $request->isXmlHttpRequest()) {
            $ticket = $entityManager->getRepository(Ticket::class)->findOneById($ticketId);

            if (!empty($ticket)) {
                $paginationResponse = $this->ticketService->paginateMembersTicketThreadCollection($ticket, $request);
    
                return new Response(json_encode($paginationResponse), 200, ['Content-Type' => 'application/json']);
            }
        }
        
        return new Response(json_encode([]), 404, ['Content-Type' => 'application/json']);
    }
    
}
