<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    const MAXIMUM_COMMENTS_BY_PAGE = 2;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findByMinMax(int $min, int $trick)
    {
        return $this->createQueryBuilder('p')
            ->where("p.trick=$trick")
            ->setMaxResults(self::MAXIMUM_COMMENTS_BY_PAGE)
            ->setFirstResult($min)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findCommentsByTrickId(int $trickId)
    {
        return $this->createQueryBuilder('p')
            ->where("p.trick=$trickId")
            ->orderBy('p.dateCreation', 'DESC')
            ->setMaxResults(self::MAXIMUM_COMMENTS_BY_PAGE)
            ->getQuery()
            ->getResult();
    }
}
