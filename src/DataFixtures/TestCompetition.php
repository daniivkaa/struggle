<?php

namespace App\DataFixtures;

use App\Entity\Competition;
use App\Entity\Player;
use App\Entity\Rating;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestCompetition extends Fixture
{

    private $hash;

    public function __construct(UserPasswordHasherInterface $hash)
    {
        $this->hash = $hash;
    }

    public function load(ObjectManager $manager)
    {
        /*
        $uer = new User();
            $uer->setEmail("daniivkaa@gmail.com");
            $uer->setFirstName("Daniil");
            $uer->setLastName("Kononov");
            $uer->setPatronymic("Alecsandrovich");
            $password = $this->hash->hashPassword($uer, "123456");
            $uer->setPassword($password);
        $manager->persist($uer);

        $competition1 = new Competition();
            $competition1->setName("Test1");
            $competition1->setIsActive(true);
            $competition1->setCode("12345");
            $competition1->setAddress("adres");
            $competition1->setDouble(false);
            $competition1->setPublic(true);
            $competition1->setType('circle');

        $competition2 = new Competition();
            $competition2->setName("Test2");
            $competition2->setIsActive(true);
            $competition2->setCode("123456");
            $competition2->setAddress("adres");
            $competition2->setDouble(true);
            $competition2->setPublic(false);
            $competition2->setType('olimp');

        $manager->persist($competition1);
        $manager->persist($competition2);

        $user = [];
        $player = [];
        $rating = [];
        for($i = 1; $i <= 4; $i++){
            $user[$i] = new User();
                $user[$i]->setEmail("test$i@gmail.com");
                $user[$i]->setFirstName("name$i");
                $user[$i]->setLastName("lastName$i");
                $user[$i]->setPatronymic("patronymic$i");
                $password = $this->hash->hashPassword($user[$i], '123456');
                $user[$i]->setPassword($password);
            $manager->persist($user[$i]);

            $player[$i] = new Player();
                $player[$i]->setFirstName("name$i");
                $player[$i]->setLastName("lastName$i");
                $player[$i]->setPatronymic("patronymic$i");
                $player[$i]->setIsActive(false);
                $player[$i]->setCompetition($competition1);
                $player[$i]->setUsers($user[$i]);
            $manager->persist($player[$i]);

            $rating[$i] = new Rating();
                $rating[$i]->setCountWin(0);
                $rating[$i]->setPlace(0);
                $rating[$i]->setPlayer($player[$i]);
                $rating[$i]->setCompetition($competition1);
            $manager->persist($rating[$i]);
        }

        for($i = 1; $i <= 4; $i++){
            $player[$i] = new Player();
                $player[$i]->setFirstName("name$i");
                $player[$i]->setLastName("lastName$i");
                $player[$i]->setPatronymic("patronymic$i");
                $player[$i]->setIsActive(false);
                $player[$i]->setCompetition($competition2);
                $player[$i]->setUsers($user[$i]);
            $manager->persist($player[$i]);

            $rating[$i] = new Rating();
                $rating[$i]->setCountWin(0);
                $rating[$i]->setPlace(0);
                $rating[$i]->setPlayer($player[$i]);
                $rating[$i]->setCompetition($competition2);
            $manager->persist($rating[$i]);
        }

        $manager->flush();
        */
    }
}
