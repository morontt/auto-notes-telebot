<?php

/**
 * User: morontt
 * Date: 26.02.2025
 * Time: 09:47
 */

namespace TeleBot\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use TeleBot\Entity\AccessToken;

class AccessTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessToken::class);
    }

    public function save(AccessToken $obj): void
    {
        $em = $this->getEntityManager();

        $em->persist($obj);
        $em->flush();
    }
}
