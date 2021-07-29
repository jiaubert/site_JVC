<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Entity\Topic;
use App\Form\CreateTopicFormType;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserTopicController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UserTopicController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/user/topic/{id}", name="user_new_topic")
     */
    public function index(Request $request, Forum $id): Response
    {
        $topic = new Topic();
        $form = $this->createForm(CreateTopicFormType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $topic->setCreatedAt(new \DateTime());
            $topic->setForum($id);

            $this->em->persist($topic);
            $this->em->flush();
            return $this->redirectToRoute('forum_detail',['id'=>$topic->getForum()->getId()]);
        }

        return $this->render('home/user_topic/index.html.twig', [
            'controller_name' => 'UserTopicController',
            'form' => $form->createView(),
            'topic'=>$topic
        ]);
    }
}
