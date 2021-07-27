<?php

namespace App\Controller;

use App\Entity\HelpTicket;
use App\Enum\HelpTicketEnum;
use App\Form\UserHelpTicket;
use App\Repository\HelpTicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserHelpTicketController extends AbstractController
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
     * UserHelpTicketController constructor.
     * @param EntityManagerInterface $em
     * @param HelpTicketRepository $helpTicketRepository
     */
    public function __construct(EntityManagerInterface $em, HelpTicketRepository $helpTicketRepository)
    {
        $this->em = $em;
        $this->helpTicketRepository = $helpTicketRepository;
    }



    // CREATE USER HELP-TICKET
    /**
     * @Route("/user/help_ticket", name="user_help_ticket")
     */
    public function index(Request $request): Response
    {
        $helpTicketEntity = new HelpTicket();
        $form = $this->createForm(UserHelpTicket::class, $helpTicketEntity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $helpTicketEntity->setCreatedAt(new \DateTime());
            $helpTicketEntity->setStatus(HelpTicketEnum::HELP_TCKET_NEW);
            $helpTicketEntity->setUser($this->getUser());

            $this->em->persist($helpTicketEntity);
            $this->em->flush();
        }

        return $this->render('user_help_ticket/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    // DISPLAY TICKETS
    /**
     * @Route("/user_tickets", name="user_tickets")
     */
    public function displayUserTicket(): Response
    {
        $helpTickets = $this->helpTicketRepository->findBy([
            'user' => $this->getUser()
        ]);

        return $this->render('user_help_ticket/userTickets.html.twig', [
            'tickets' => $helpTickets,
        ]);
    }


}
