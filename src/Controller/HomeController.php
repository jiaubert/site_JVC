<?php

namespace App\Controller;

use App\Enum\HelpTicketEnum;
use App\Repository\ForumRepository;
use App\Repository\PostRepository;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @var PostRepository $postRepository
     */
    private $postRepository;

    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * @var ForumRepository
     */
    private $forumRepo;

    /**
     * @var TopicRepository
     */
    private $topicRepo;

    /**
     * HomeController constructor.
     * @param PostRepository $postRepository
     * @param EntityManagerInterface $em
     * @param ForumRepository $forumRepo
     * @param TopicRepository $topicRepo
     */
    public function __construct(PostRepository $postRepository, EntityManagerInterface $em, ForumRepository $forumRepo, TopicRepository $topicRepo)
    {
        $this->postRepository = $postRepository;
        $this->em = $em;
        $this->forumRepo = $forumRepo;
        $this->topicRepo = $topicRepo;
    }

    // LIST ALL POSTS
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $postEntities = $this->postRepository->findAll();

        return $this->render('home/index.html.twig', [
            'posts' => $postEntities,
        ]);
    }

    // POST DETAIL
    /**
     * @Route("/post_detail/{id}", name="post_detail")
     */
    public function displayPostDetail($id): Response
    {
        $postEntity = $this->postRepository->find($id);

        $postEntity->setNumberView($postEntity->getNumberView()+1);
        $this->em->persist($postEntity);
        $this->em->flush();

        return $this->render('home/postDetail.html.twig', [
            'post' => $postEntity,
        ]);
    }

    //forum detail (list topic)
    /**
     * @Route("/forum_detail/{id}", name="forum_detail")
     */
    public function displayForumDetail(int $id): Response
    {
        $forumEntity = $this->forumRepo->find($id);
        $topicEntities = $this->topicRepo->findAll();
        dump($topicEntities);

        return $this->render('home/forum_front/forumDetail.html.twig', [
            'forum' => $forumEntity,
        ]);
    }
}
