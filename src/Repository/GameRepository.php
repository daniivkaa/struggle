<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository implements GameRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function getGamesForAjax(int $competitionId): array
    {
        return $this->createQueryBuilder('g')
            ->select('f.firstName as firstPlayer', 's.firstName  as secondPlayer')
            ->innerJoin("g.firstPlayer", "f")
            ->innerJoin("g.secondPlayer", "s")
            ->andWhere('g.competition = :competitionId')
            ->setParameter('competitionId', $competitionId)
            ->andWhere('g.isActive = true')
            ->getQuery()
            ->getResult()
            ;
    }
}
