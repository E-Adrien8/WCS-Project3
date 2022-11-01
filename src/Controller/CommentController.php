<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Event;
use App\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route(path: '/user/event/{eventId}/comment/{commentId}/supprimer', name: 'app_delete_comment', methods: ['GET'])]
    #[Entity('event', options: ['id' => 'eventId'])]
    #[Entity('comment', options: ['id' => 'commentId'])]
    public function deleteComment(CommentRepository $commentRepository, Comment $comment, Event $event): Response
    {
        $commentRepository->remove($comment, true);
        $this->addFlash('success', 'votre commentaire a été supprimé');


        return $this->redirectToRoute('app_event', [
            'id' => $event->getId(),
        ]);
    }
}
