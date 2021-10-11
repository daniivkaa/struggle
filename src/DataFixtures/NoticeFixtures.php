<?php

namespace App\DataFixtures;

use App\Entity\Competition;
use App\Entity\Notice;
use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NoticeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        //Для лидеров сореновваний
        $competitions = $manager->getRepository(Competition::class)->findBy(["isActive" => true]);
        foreach($competitions as $competition){
            $lider = $competition->getLider();

            $notice = new Notice();
            $notice->setType("admin_competition");
            $notice->setTargetUser($lider);
            $notice->setCompetition($competition);
            $notice->setActive(true);

            $manager->persist($notice);
        }

        //Для участников соревнований
        $players = $manager->getRepository(Player::class)->findAll();
        foreach($players as $player){
            $competition = $player->getCompetition();
            $user = $player->getUsers();

            if(!$competition->getIsActive()){
                continue;
            }

            $notice = new Notice();
                $notice->setType("competition");
                $notice->setTargetUser($user);
                $notice->setCompetition($competition);
                $notice->setActive(true);
            $manager->persist($notice);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CompetitionFixtures::class,
            PlayerFixtures::class
        ];
    }
}
