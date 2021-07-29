<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMessageController extends AbstractController
{
    /**
     * @var MessageRepository
     */
    private $messageRepo;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * AdminMessageController constructor.
     * @param MessageRepository $messageRepo
     * @param EntityManagerInterface $em
     */
    public function __construct(MessageRepository $messageRepo, EntityManagerInterface $em)
    {
        $this->messageRepo = $messageRepo;
        $this->em = $em;
    }

    /**
     * @Route("/admin/message", name="admin_message")
     */
    public function index(): Response
    {
        $messageRepo = $this->messageRepo->findAll();
        return $this->render('admin/admin_message/index.html.twig', [
            'controller_name' => 'AdminMessageController',
            'listeMessages' => $messageRepo
        ]);
    }

    /**
     * @Route("/admin/delete_message/{id}", name="admin_delete_message")
     */
    public function deleteMessage(int $id): Response
    {
        $messageEntity = $this->messageRepo->find($id);
        $this->em->remove($messageEntity);
        $this->em->flush();

        return $this->redirectToRoute('admin_topic');
    }
}
