<?php

namespace App\Controller\Ajax;

use App\Entity\User;
use App\Repository\CommentRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    private $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository){
        $this->commentRepository = $commentRepository;
    }

    /**
     * @Route("/user/comments/ajax/{targetUser}/{secondUser}/{messageId}", name="get_messages_ajax", methods={"GET","POST"})
     */
    public function getMessagesAction(User $targetUser, User $secondUser, int $messageId): JsonResponse
    {
        $targetUserId = $targetUser->getId();
        $secondUserId = $secondUser->getId();

        $comments = $this->commentRepository->getCommentsForAjax($targetUserId, $secondUserId, $messageId);

        return $this->json($comments);
    }
}