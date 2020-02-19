<?php

namespace App\Repository;

use App\Entity\EmailLinkToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EmailLinkToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailLinkToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailLinkToken[]    findAll()
 * @method EmailLinkToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailLinkTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailLinkToken::class);
    }

}
