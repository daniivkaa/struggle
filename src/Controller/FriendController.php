<?php

namespace App\Controller;

use App\Entity\Friend;
use App\Entity\Notice;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendController extends AbstractController
{

    /**
     * @Route("/add/friend/{secondUser}", name="add_friend")
     */
    public function addFriend(User $secondUser, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        $friend = $em->getRepository(Friend::class)->findOneBy(["targetUser" => $user, "secondUser" => $secondUser]);
        $notice = $em->getRepository(Notice::class)->findOneBy(["targetUser" => $user, "secondUser" => $secondUser, 'active' => true]);
        if($friend || !$notice){
            return $this->redirectToRoute("user_show", ['secondUser' => $secondUser->getId()]);
        }
        $notice->setActive(false);
        $em->persist($notice);

        $targetFriend = new Friend();
        $secondFriend = new Friend();

        $targetFriend->setTargetUser($user);
        $targetFriend->setSecondUser($secondUser);
        $em->persist($targetFriend);

        $secondFriend->setTargetUser($secondUser);
        $secondFriend->setSecondUser($user);
        $em->persist($secondFriend);

        $em->flush();

        return $this->redirectToRoute("notice");
    }
}
