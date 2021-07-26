<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserHelpTicketController extends AbstractController
{

    // USER SEND TICKET
    /**
     * @Route("/user/help_ticket", name="user_help_ticket")
     */
    public function index(): Response
    {
        return $this->render('user_help_ticket/index.html.twig', [
            'controller_name' => 'UserHelpTicketController',
        ]);
    }




// Quand le message a été vu / fermé, modifier son status avec un SET et :
// HelpTicketEnum::HELP_TCIKET_READ
}
