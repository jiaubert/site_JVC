<?php

namespace App\Controller;

use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumFrontController extends AbstractController
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
     * ForumFrontController constructor.
     * @param EntityManagerInterface $em
     * @param ForumRepository $forumRepo
     */
    public function __construct(EntityManagerInterface $em, ForumRepository $forumRepo)
    {
        $this->em = $em;
        $this->forumRepo = $forumRepo;
    }

    /**
     * @Route("/forum/front", name="forum_front")
     */
    public function index(): Response
    {
        $forumEntities = $this->forumRepo->findAll();

        return $this->render('home/forum_front/index.html.twig', [
            'controller_name' => 'ForumFrontController',
            'forums' => $forumEntities
        ]);
    }


}
