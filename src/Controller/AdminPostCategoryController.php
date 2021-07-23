<?php

namespace App\Controller;

use App\Entity\PostCategory;
use App\Form\CreatePostCategoryFormType;
use App\Repository\PostCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostCategoryController extends AbstractController
{
    /**
     * @var PostCategoryRepository $postCategoryEntities
     */
    private $postCategoryEntities;

    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * AdminPostCategoryController constructor.
     * @param PostCategoryRepository $postCategoryEntities
     * @param EntityManagerInterface $em
     */
    public function __construct(PostCategoryRepository $postCategoryEntities, EntityManagerInterface $em)
    {
        $this->postCategoryEntities = $postCategoryEntities;
        $this->em = $em;
    }



    // LIST ALL POST-CATEGORIES
    /**
     * @Route("/admin/post_categories", name="admin_post_categories")
     */
    public function index(): Response
    {
        $postCategoryEntities = $this->postCategoryEntities->findAll();

        return $this->render('admin_post_category/index.html.twig', [
            'postCategories' => $postCategoryEntities,
        ]);
    }

    // CREATE NEW POST-CATEGORY
    /**
     * @Route("/admin/new_post_category", name="admin_new_post_category")
     */
    public function createNewPostCategory(Request $request): Response
    {
        $postCategoryEntity = new PostCategory();
        $form = $this->createForm(CreatePostCategoryFormType::class, $postCategoryEntity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())  {
            $this->em->persist($postCategoryEntity);
            $this->em->flush();
            return $this->redirectToRoute('admin_post_categories');
        }

        return $this->render('admin_post_category/newCategory.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // MODIFY POST-CATEGORY
    /**
     * @Route("/admin/update_post_category/{id}", name="admin_update_post_category")
     */
    public function modifyPostCategory(Request $request, $id): Response
    {
        $postCategoryEntity = $this->postCategoryEntities->find($id);
        $form = $this->createForm(CreatePostCategoryFormType::class, $postCategoryEntity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())  {
            $this->em->persist($postCategoryEntity);
            $this->em->flush();
            return $this->redirectToRoute('admin_post_categories');
        }

        return $this->render('admin_post_category/newCategory.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    // DELETE POST-CATEGORY
    /**
     * @Route("/admin/delete_post_category/{id}", name="admin_delete_post_category")
     */
    public function deletePostCategory($id): Response
    {
        $postCategoryEntity = $this->postCategoryEntities->find($id);
        $postCategoryName = $postCategoryEntity->getName();
        $this->em->remove($postCategoryEntity);
        $this->em->flush();

        return $this->redirectToRoute('admin_post_categories', [
            'deleteMessage' => $postCategoryName . ' successfully deleted!',
        ]);
    }






}
