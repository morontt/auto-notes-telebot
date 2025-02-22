<?php

/**
 * User: morontt
 * Date: 22.02.2025
 * Time: 17:28
 */

namespace TeleBot\Repository;

use DateInterval;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use TeleBot\Entity\Code;

class CodeRepository extends ServiceEntityRepository
{
    public const LIFETIME = 'P2D';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Code::class);
    }

    public function save(Code $code): void
    {
        $em = $this->getEntityManager();

        $em->persist($code);
        $em->flush();
    }

    public function getByNotExpiredCode(string $random): ?Code
    {
        $qb = $this->createQueryBuilder('c');

        $from = (new DateTime())->sub(new DateInterval(self::LIFETIME));

        $qb
            ->where($qb->expr()->eq('c.code', ':code'))
            ->andWhere($qb->expr()->gt('c.createdAt', ':from'))
            ->setParameter('code', $random)
            ->setParameter('from', $from)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
