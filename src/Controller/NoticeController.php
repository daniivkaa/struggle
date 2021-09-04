<?php

namespace App\Controller;

use App\Entity\Friend;
use App\Entity\Notice;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoticeController extends AbstractController
{
    /**
     * @Route("/user/notice", name="notice")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $notices = $em->getRepository(Notice::class)->findBy(["targetUser" => $user->getId(), 'active' => true]);
        return $this->render('user/notice.html.twig', [
            'notices' => $notices,
        ]);
    }

    /**
     * @Route("/notice/create/{secondUser}", name="notice_crete_friend")
     */
    public function createNotice(User $secondUser, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $isFriend = $em->getRepository(Friend::class)->findOneBy(["targetUser" => $user, "secondUser" => $secondUser]);
        $isNotice = $em->getRepository(Notice::class)->findOneBy(["secondUser" => $user, "targetUser" => $secondUser]);

        if($isFriend || $isNotice || $user->getId() ==  $secondUser->getId()){
            return $this->redirectToRoute("user_show", ['secondUser' => $secondUser->getId()]);
        }

        $notice = new Notice();
            $notice->setType("friend");
            $notice->setTargetUser($secondUser);
            $notice->setSecondUser($user);
            $notice->setActive(true);

        $em->persist($notice);
        $em->flush();

        return $this->redirectToRoute("user_show", ['secondUser' => $secondUser->getId()]);
    }
}
