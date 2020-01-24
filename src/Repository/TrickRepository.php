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

    const MAXIMUM_RESULT = 10;


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }


    public function findByMinMax(int $min)
    {
      return $this->createQueryBuilder('p')
            ->where('p.publicated=true')
            ->setMaxResults(self::MAXIMUM_RESULT)
            ->setFirstResult($min)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }


}
