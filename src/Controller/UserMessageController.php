<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Topic;
use App\Form\CreateMessageFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserMessageController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UserMessageController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/user/new_message/{id}", name="user_new_message")
     */
    public function newMessage(Request $request, Topic $id): Response
    {
        $message = new Message();
        $form = $this->createForm(CreateMessageFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $message->setCreatedAt(new \DateTime());
            $message->setTopic($id);
            $message->setUser($this->getUser('id'));

            $this->em->persist($message);
            $this->em->flush();
            return $this->redirectToRoute('topic_detail',['id'=>$message->getTopic()->getId()]);
        }

        return $this->render('home/user_message/index.html.twig', [
            'controller_name' => 'UserTopicController',
            'form' => $form->createView(),
            'topic'=>$message
        ]);
    }
}
