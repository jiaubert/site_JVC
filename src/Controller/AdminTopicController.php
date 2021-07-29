<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Form\AdminCreateTopicFormType;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTopicController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TopicRepository
     */
    private $topicRepo;

    /**
     * AdminTopicController constructor.
     * @param EntityManagerInterface $em
     * @param TopicRepository $topicRepo
     */
    public function __construct(EntityManagerInterface $em, TopicRepository $topicRepo)
    {
        $this->em = $em;
        $this->topicRepo = $topicRepo;
    }

    /**
     * @Route("/admin/topic", name="admin_topic")
     */
    public function index(): Response
    {
        $topicRepo = $this->topicRepo->findAll();

        return $this->render('admin/admin_topic/index.html.twig', [
            'controller_name' => 'AdminTopicController',
            'listeTopics' => $topicRepo
        ]);
    }

    /**
     * @Route("/admin/update_topic/{id}", name="admin_update_topic")
     */
    public function updateTopic(Request $request, int $id): Response
    {
        $topicEntity = $this->topicRepo->find($id);
        $form = $this->createForm(AdminCreateTopicFormType::class,$topicEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $topicEntity->setCreatedAt(new \DateTime());

            $this->em->persist($topicEntity);
            $this->em->flush();

            return $this->redirectToRoute('admin_topic');
        }

        return $this->render('admin/admin_topic/newTopic.html.twig', [
            'controller_name' => 'AdminTopicController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/delete_topic/{id}", name="admin_delete_topic")
     */
    public function deleteTopic(Request $request, int $id): Response
    {
        $topicEntity = $this->topicRepo->find($id);
        $topicTitle = $topicEntity->getTitle();
        $this->em->remove($topicEntity);
        $this->em->flush();

        return $this->redirectToRoute('admin_topic', [
            'deleteMessage' => $topicTitle . ' successfully deleted!',
        ]);
    }

    /**
     * @Route("/admin/newTopic", name="admin_create_topic")
     */
    public function createTopic(Request $request): Response
    {
        $topicEntity = new Topic();
        $form = $this->createForm(AdminCreateTopicFormType::class,$topicEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $topicEntity->setCreatedAt(new \DateTime());

            $this->em->persist($topicEntity);
            $this->em->flush();

            return $this->redirectToRoute('forum_detail',['id'=>$topicEntity->getForum()->getId()]);
        }

        return $this->render('admin/admin_topic/newTopic.html.twig', [
            'controller_name' => 'AdminTopicController',
            'form' => $form->createView()
        ]);
    }
}
