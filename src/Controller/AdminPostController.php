<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\CreatePostFormType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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


    // LIST ALL POSTS
    /**
     * @Route("/admin/posts", name="admin_posts")
     */
    public function listAllPosts(): Response
    {
        $postEntities = $this->postRepository->findAll();
        return $this->render('admin/admin_post/index.html.twig', [
            'postEntities' => $postEntities,
        ]);
    }

    // POST DETAIL
    /**
     * @Route("/post_detail/{id}", name="post_detail")
     */
    public function displayPostDetail($id): Response
    {
        $postEntity = $this->postRepository->find($id);
        return $this->render('admin/admin_post/detailPost.html.twig', [
            'post' => $postEntity,
        ]);
    }

    // CREATE NEW POST
    /**
     * @Route("/admin/new_post", name="admin_new_post")
     */
    public function createNewPost(Request $request): Response
    {
        $postEntity = new Post();
        $form = $this->createForm(CreatePostFormType::class, $postEntity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $postEntity->setCreatedAt(new \DateTime());
            $postEntity->setUser($this->getUser());
            $postEntity->setNumberView(0);
            $postEntity->setStatus(1);

            $this->em->persist($postEntity);
            $this->em->flush();
            return $this->redirectToRoute('admin_posts');
        }

        return $this->render('admin/admin_post/newPost.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    //MODIFY POST
    /**
     * @Route("/admin/update_post/{id}", name="admin_update_post")
     */
    public function modifyPost(Request $request, $id): Response
    {
        $postEntity = $this->postRepository->find($id);
        $form = $this->createForm(CreatePostFormType::class, $postEntity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $postEntity->setUser($this->getUser());

            $this->em->persist($postEntity);
            $this->em->flush();
            return $this->redirectToRoute('admin_posts');
        }

        return $this->render('admin/admin_post/newPost.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    // DELETE POST
    /**
     * @Route("/admin/delete_post/{id}", name="admin_delete_post")
     */
    public function deletePost($id): Response
    {
        $postEntity = $this->postRepository->find($id);
        $postTitle = $postEntity->getTitle();
        $this->em->remove($postEntity);
        $this->em->flush();

        return $this->redirectToRoute('admin_posts', [
            'deleteMessage' => $postTitle . ' successfully deleted!',
        ]);
    }



}
