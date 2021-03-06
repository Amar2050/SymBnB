<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Service\Pagination;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comment/{page<\d+>?1}", name="admin_comments_index")
     */
    public function index(CommentRepository $repo, $page, Pagination $pagination)
    {
        $pagination->setEntityClass(Comment::class)
                   ->setLimit(7)
                   ->setPage($page);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Admin edit comment function 
     *
     * @Route("/admin/comment/{id}/edit" , name="admin_comment_edit")
     * @param Comment $comment
     * @return Response
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager){
        $form= $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire <strong>{$comment->getId()}</strong> a bien été modifié !"
            );
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment'   => $comment,
            'form' => $form->createView()

        ]);
    }

    /**
     * Deleting an comment
     * 
     * @Route("/admin/comment/{id}/delete",name="admin_comment_delete")
     * 
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Comment $comment, ObjectManager $manager) {
        
            $manager->remove($comment);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "Le commentaire de {$comment->getAuthor()->getFullName()} a bien été supprimée !"
            );
        


        return $this->redirectToRoute('admin_comments_index');
    }
}
