<?php

namespace App\DataFixtures;

use App\Entity\Competition;
use App\Entity\Player;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PlayerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $emails = [
            "test1" => "test1@gmail.com",
            "test2" => "test2@gmail.com",
            "test3" => "test3@gmail.com",
            "test4" => "test4@gmail.com",
        ];
        $competitions = $manager->getRepository(Competition::class)->findAll();

        foreach($competitions as $competition){
            foreach($emails as $email){
                $user = $manager->getRepository(User::class)->findOneBy(["email" => $email]);

                $player = new Player();
                $player->setFirstName($user->getFirstName());
                $player->setLastName($user->getLastName());
                $player->setPatronymic($user->getPatronymic());
                $player->setIsActive(false);
                $player->setCompetition($competition);
                $player->setUsers($user);
                $manager->persist($player);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CompetitionFixtures::class
        ];
    }
}
