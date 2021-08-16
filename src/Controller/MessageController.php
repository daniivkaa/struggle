<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Message;
use App\Entity\User;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/user/messages/{user}", name="messages")
     */
    public function index(User $user, EntityManagerInterface $em): Response
    {
        $messages = $em->getRepository(Message::class)->findBy(['targetUser' => $user]);

        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/user/message/{secondUser}", name="user_message")
     */
    public function message(User $secondUser, EntityManagerInterface $em, Request $request)
    {
        $targetUser = $this->getUser();

        $message = $em->getRepository(Message::class)->findOneBy(['targetUser' => $targetUser, 'secondUser' => $secondUser]);
        $secondMessage = $em->getRepository(Message::class)->findOneBy(['targetUser' => $secondUser, 'secondUser' => $targetUser]);
        if(!$message){
            $message = new Message();
            $message->setTargetUser($targetUser);
            $message->setSecondUser($secondUser);

            $em->persist($message);

            $secondMessage = new Message();
            $secondMessage->setTargetUser($secondUser);
            $secondMessage->setSecondUser($targetUser);

            $em->persist($secondMessage);
        }

        $comment = new Comment();
        $secondComment = new Comment();
        $commentForm= $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()){
            $content = $commentForm->get("content")->getData();
            $comment->setContent($content);
            $comment->setTargetUser($targetUser);
            $comment->setSecondUser($secondUser);
            $comment->setMessage($message);

            $em->persist($comment);

            $secondComment->setContent($content);
            $secondComment->setTargetUser($targetUser);
            $secondComment->setSecondUser($secondUser);
            $secondComment->setMessage($secondMessage);

            $em->persist($secondComment);
            $em->flush();

            return $this->redirectToRoute("user_message", ['secondUser' => $secondUser->getId()]);
        }

        $em->flush();

        return $this->render('user/message.html.twig', [
            'message' => $message,
            'targetUser' => $targetUser,
            'secondUser' => $secondUser,
            'commentForm' => $commentForm->createView(),
        ]);
    }
}
