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
        $usersInfo = [
            [
                "email" => "daniivkaa@gmail.com",
                "name" => "Даниил",
                "lastName" => "Кононов",
                "patronymic" => "Александрович",
                "password" => "123456"
            ],
            [
                "email" => "test1@gmail.com",
                "name" => "Ваня1",
                "lastName" => "Муражкин",
                "patronymic" => "Иванович",
                "password" => "123456"
            ],
            [
                "email" => "test2@gmail.com",
                "name" => "Саша2",
                "lastName" => "Колмогоров",
                "patronymic" => "Одисеевич",
                "password" => "123456"
            ],
            [
                "email" => "test3@gmail.com",
                "name" => "Вадим3",
                "lastName" => "Несмеянов",
                "patronymic" => "Егорович",
                "password" => "123456"
            ],
            [
                "email" => "test4@gmail.com",
                "name" => "Оля4",
                "lastName" => "Кузницова",
                "patronymic" => "Кировна",
                "password" => "123456"
            ],
        ];

        foreach($usersInfo as $userInfo){
            $user = new User();
                $user->setEmail($userInfo["email"]);
                $user->setFirstName($userInfo["name"]);
                $user->setLastName($userInfo["lastName"]);
                $user->setPatronymic($userInfo["patronymic"]);
                $password = $this->hash->hashPassword($user, $userInfo["password"]);
                $user->setPassword($password);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
