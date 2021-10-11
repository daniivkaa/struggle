<?php

namespace App\DataFixtures;

use App\Entity\Friend;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FriendFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $friendsInfo = [
            [
                "targetUser" => "daniivkaa@gmail.com",
                "friends" => [
                    "user1" => "test1@gmail.com",
                    "user2" => "test2@gmail.com",
                    "user3" => "test3@gmail.com",
                    "user4" => "test4@gmail.com",
                ]
            ],
        ];

        foreach($friendsInfo as $friendInfo){
            $targetUser = $manager->getRepository(User::class)->findOneBy(["email" => $friendInfo["targetUser"]]);

            foreach($friendInfo["friends"] as $friend){
                $secondUser = $manager->getRepository(User::class)->findOneBy(["email" => $friend]);
                $targetFriend = new Friend();
                $secondFriend = new Friend();

                $targetFriend->setTargetUser($targetUser);
                $targetFriend->setSecondUser($secondUser);
                $manager->persist($targetFriend);

                $secondFriend->setTargetUser($secondUser);
                $secondFriend->setSecondUser($targetUser);
                $manager->persist($secondFriend);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
