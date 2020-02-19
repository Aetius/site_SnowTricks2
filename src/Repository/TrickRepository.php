<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{

    const MAXIMUM_RESULT_BY_PAGE = 10;


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }


    public function findByMinMax(int $min)
    {
        return $this->createQueryBuilder('p')
            ->where('p.publicated=true')
            ->setMaxResults(self::MAXIMUM_RESULT_BY_PAGE)
            ->setFirstResult($min)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findLast()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }


    public function getFirstPublicatedTricksPage()
    {
        return $this->createQueryBuilder('p')
            ->where('p.publicated=true')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(self::MAXIMUM_RESULT_BY_PAGE)
            ->getQuery()
            ->getResult();
    }


}
