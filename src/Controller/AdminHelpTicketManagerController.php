<?php

namespace App\Controller;

use App\Enum\HelpTicketEnum;
use App\Repository\HelpTicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminHelpTicketManagerController extends AbstractController
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * @var HelpTicketRepository $helpTicketRepository
     */
    private $helpTicketRepository;

    /**
     * AdminHelpTicketManagerController constructor.
     * @param EntityManagerInterface $em
     * @param HelpTicketRepository $helpTicketRepository
     */
    public function __construct(EntityManagerInterface $em, HelpTicketRepository $helpTicketRepository)
    {
        $this->em = $em;
        $this->helpTicketRepository = $helpTicketRepository;
    }


    // DISPLAY TICKETS
    /**
     * @Route("/admin/ticket_manager", name="admin_help_ticket_manager")
     */
    public function index(): Response
    {
        $helpTickets = $this->helpTicketRepository->findAll();

        return $this->render('admin_help_ticket_manager/index.html.twig', [
            'helpTickets' => $helpTickets,
        ]);
    }

    // CHECK TICKET DETAIL
    /**
     * @Route("/admin/ticket_details/{id}", name="admin_help_ticket_details")
     */
    public function checkTicketDetails($id): Response
    {
        $ticketEntity = $this->helpTicketRepository->find($id);
        $ticketEntity->setStatus(HelpTicketEnum::HELP_TCIKET_READ);
        $ticketEntity->setTreatedOn(new \DateTime());

        $this->em->persist($ticketEntity);
        $this->em->flush();

        return $this->render('admin_help_ticket_manager/detail_ticket.html.twig', [
            'ticket' => $ticketEntity,
        ]);
    }

    // CLOSE TICKET
    /**
     * @Route("/admin/close_ticket/{id}", name="admin_close_ticket")
     */
    public function closeTicket($id): Response
    {
        $ticketEntity = $this->helpTicketRepository->find($id);
        $ticketEntity->setStatus(HelpTicketEnum::HELP_TCIKET_TREATED);
        $ticketEntity->setTreatedOn(new \DateTime());

        $this->em->persist($ticketEntity);
        $this->em->flush();

        return $this->redirectToRoute('admin_help_ticket_manager', [
            'ticket' => $ticketEntity,
        ]);
    }


}
