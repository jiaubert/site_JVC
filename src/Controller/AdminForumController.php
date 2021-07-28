<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Form\CreateForumFormType;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminForumController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ForumRepository
     */
    private $forumRepo;

    /**
     * AdminForumController constructor.
     * @param EntityManagerInterface $em
     * @param ForumRepository $forumRepo
     */
    public function __construct(EntityManagerInterface $em, ForumRepository $forumRepo)
    {
        $this->em = $em;
        $this->forumRepo = $forumRepo;
    }

    // list all forum
    /**
     * @Route("/admin/forum", name="admin_forum")
     */
    public function index(): Response
    {
        $forumRepo = $this->forumRepo->findAll();

        return $this->render('admin/admin_forum/index.html.twig', [
            'listeForums' => $forumRepo
        ]);
    }

    //create forum
    /**
     * @Route("/admin/newForum", name="admin_create_forum")
     */
    public function createForum(Request $request): Response
    {
       $forumEntity = new Forum();
       $form = $this->createForm(CreateForumFormType::class, $forumEntity);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()){
           $forumEntity->setCreatedAt(new \DateTime());

           $this->em->persist($forumEntity);
           $this->em->flush();

           return $this->redirectToRoute('admin_forum');
       }

        return $this->render('admin/admin_forum/newForum.html.twig', [
            'controller_name' => 'AdminForumController',
            'form' => $form->createView()
        ]);
    }

    //update forum
    /**
     * @Route("/admin/update_forum/{id}", name="admin_update_forum")
     */
    public function updateForum(Request $request, int $id): Response
    {
        $forumEntity = $this->forumRepo->find($id);
        $form = $this->createForm(CreateForumFormType::class, $forumEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $forumEntity->setCreatedAt(new \DateTime());

            $this->em->persist($forumEntity);
            $this->em->flush();

            return $this->redirectToRoute('admin_forum');
        }

        return $this->render('admin/admin_forum/newForum.html.twig', [
            'controller_name' => 'AdminForumController',
            'form' => $form->createView()
        ]);
    }

    //delete forum
    /**
     * @Route("/admin/delete_forum/{id}", name="admin_delete_forum")
     */
    public function deleteForum(Request $request, int $id): Response
    {
        $forumEntity = $this->forumRepo->find($id);
        $forumTitle = $forumEntity->getTitle();
        $this->em->remove($forumEntity);
        $this->em->flush();

        return $this->redirectToRoute('admin_forum', [
            'deleteMessage' => $forumTitle . ' successfully deleted!',
        ]);
    }
}
