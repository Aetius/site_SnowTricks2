<?php

namespace App\Repository;

use App\Entity\TokenResetPassword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TokenResetPassword|null find($id, $lockMode = null, $lockVersion = null)
 * @method TokenResetPassword|null findOneBy(array $criteria, array $orderBy = null)
 * @method TokenResetPassword[]    findAll()
 * @method TokenResetPassword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokenResetPasswordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TokenResetPassword::class);
    }

    // /**
    //  * @return TokenResetPassword[] Returns an array of TokenResetPassword objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TokenResetPassword
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
