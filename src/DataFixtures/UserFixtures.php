<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $hash;

    public function __construct(UserPasswordHasherInterface $hash)
    {
        $this->hash = $hash;
    }

    public function load(ObjectManager $manager)
    {
        $users = [
            [
                "mail" => "daniivkaa@gmail.com",

            ],
            [],
            [],
            [],
            [],
        ];

        $uer = new User();
            $uer->setEmail("daniivkaa@gmail.com");
            $uer->setFirstName("Даниил");
            $uer->setLastName("Кононов");
            $uer->setPatronymic("Александрович");
            $password = $this->hash->hashPassword($uer, "123456");
            $uer->setPassword($password);
        $manager->persist($uer);

        $manager->flush();
    }
}
