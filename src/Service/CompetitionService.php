<?php

namespace App\Service;

use App\Entity\Competition;
use App\Entity\User;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;

class CompetitionService
{
    private $em;
    private $ratingRepository;

    public function __construct(EntityManagerInterface $em, RatingRepository $ratingRepository)
    {
        $this->em = $em;
        $this->ratingRepository = $ratingRepository;

    }

    public function endCompetition(Competition $competition):void
    {
        $ratings = $this->ratingRepository->findBy(['competition' => $competition], ['countWin' => 'DESC']);
        $i = 1;

        foreach($ratings as $rating) {
            $rating->setPlace($i);
            $this->em->persist($rating);
            $i++;
        }

        $competition->setIsActive(false);
        $this->em->persist($competition);

        $this->em->flush();
    }

    public function createCompetition(Competition $competition, User $lider):void
    {
        $competition->setCode(md5(time()));
        $competition->setIsActive(true);
        $competition->setLider($lider);

        $this->em->persist($competition);
        $this->em->flush();
    }
}