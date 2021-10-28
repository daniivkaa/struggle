<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository implements CommentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getCommentsForAjax(int $targetUserId, int $secondUserId, int $messageId): array
    {
        return $this->createQueryBuilder('c')
            ->select("c.content", "t.firstName")
            ->innerJoin("c.targetUser", "t")
            ->andWhere("c.targetUser = :targetUserId AND c.secondUser = :secondUserId")
            ->setParameter("targetUserId", $targetUserId)
            ->setParameter("secondUserId", $secondUserId)
            ->orWhere("c.targetUser = :secondUserId")
            ->setParameter("secondUserId", $secondUserId)
            ->orWhere("c.secondUser = :targetUser")
            ->setParameter("targetUser", $secondUserId)
            ->andWhere("c.message = :messageId")
            ->setParameter("messageId", $messageId)
            ->orderBy("c.id", "ASC")
            ->getQuery()
            ->getResult()
            ;
    }
}
