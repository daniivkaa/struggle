<?php

namespace App\DataFixtures;

use App\Entity\Competition;
use App\Entity\Rating;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RatingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $competitions = $manager->getRepository(Competition::class)->findAll();

        foreach($competitions as $competition){
            $players = $competition->getPlayers();
            foreach($players as $player){
                $rating = new Rating();
                    $rating->setCountWin(0);
                    $rating->setPlace(0);
                    $rating->setPlayer($player);
                    $rating->setCompetition($competition);
                $manager->persist($rating);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CompetitionFixtures::class
        ];
    }
}
