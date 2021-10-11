<?php

namespace App\DataFixtures;

use App\Entity\Competition;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CompetitionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $competitionsInfo = [
            [
                "lider1" => "daniivkaa@gmail.com",
                "name" => "Командные ЛГТУ",
                "description" => "Все курсы участвуют",
                "adres" => "ЛГТУ",
                "code" => "12345",
                "day" => (new \DateTime())->modify('+1 month'),
                "time" => (new \DateTime())->modify('+1 month'),
                "active" => false,
                "double" => false,
                "public" => true,
                "type" => "circle",
            ],
            [
                "lider1" => "daniivkaa@gmail.com",
                "name" => "Первенство ЛГТУ",
                "description" => "Участвуют толко 3 и 4 курс",
                "adres" => "ЛГТУ",
                "code" => "123456",
                "day" => (new \DateTime())->modify('+1 month'),
                "time" => (new \DateTime())->modify('+1 month'),
                "active" => true,
                "double" => false,
                "public" => true,
                "type" => "circle",
            ],
        ];

        foreach($competitionsInfo as $competitionInfo){
            $user = $manager->getRepository(User::class)->findOneBy(["email" => $competitionInfo["lider1"]]);

            $competition = new Competition();
                $competition->setLider($user);
                $competition->setName($competitionInfo["name"]);
                $competition->setDescription($competitionInfo["description"]);
                $competition->setAddress($competitionInfo["adres"]);
                $competition->setCode($competitionInfo["code"]);
                $competition->setDay($competitionInfo["day"]);
                $competition->setTime($competitionInfo["time"]);
                $competition->setIsActive($competitionInfo["active"]);
                $competition->setDouble($competitionInfo["double"]);
                $competition->setPublic($competitionInfo["public"]);
                $competition->setType($competitionInfo["type"]);
            $manager->persist($competition);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
