<?php

namespace App\Controller;

use App\Repository\PostRepository;
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
     * HomeController constructor.
     * @param PostRepository $postRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(PostRepository $postRepository, EntityManagerInterface $em)
    {
        $this->postRepository = $postRepository;
        $this->em = $em;
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


}
