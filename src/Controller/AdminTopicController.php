<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTopicController extends AbstractController
{
    /**
     * @Route("/admin/topic", name="admin_topic")
     */
    public function index(): Response
    {
        return $this->render('admin_topic/index.html.twig', [
            'controller_name' => 'AdminTopicController',
        ]);
    }
}
