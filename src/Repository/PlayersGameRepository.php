<?php

namespace App\Repository;

use App\Entity\PlayersGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlayersGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayersGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayersGame[]    findAll()
 * @method PlayersGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayersGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayersGame::class);
    }

    // /**
    //  * @return PlayersGame[] Returns an array of PlayersGame objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlayersGame
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
