<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostController extends AbstractController
{


    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * @var PostRepository $postRepository
     */
    private $postRepository;

    /**
     * AdminPostController constructor.
     * @param EntityManagerInterface $em
     * @param PostRepository $postRepository
     */
    public function __construct(EntityManagerInterface $em, PostRepository $postRepository)
    {
        $this->em = $em;
        $this->postRepository = $postRepository;
    }


    /**
     * @Route("/admin/post", name="admin_post")
     */
    public function index(): Response
    {
        return $this->render('admin_post/index.html.twig', [
            'controller_name' => 'AdminPostController',
        ]);
    }
}
