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
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use TeleBot\Entity\Code;

/**
 * @extends ServiceEntityRepository<Code>
 */
class CodeRepository extends ServiceEntityRepository
{
    public const LIFETIME = 'P2D';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Code::class);
    }

    public function save(Code $obj): void
    {
        $em = $this->getEntityManager();

        $em->persist($obj);
        $em->flush();
    }

    public function getByNotExpiredCode(string $random): ?Code
    {
        return $this->getNorExpiredQueryBuilder($random)->getQuery()->getOneOrNullResult();
    }

    public function getLastByUser(int $id): ?Code
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->where($qb->expr()->eq('c.userId', ':id'))
            ->setParameter('id', $id)
            ->addOrderBy('c.id', 'DESC')
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getUnusedCode(mixed $code): ?Code
    {
        $qb = $this->getNorExpiredQueryBuilder($code);
        $qb->andWhere($qb->expr()->isNull('c.userId'));

        return $qb->getQuery()->getOneOrNullResult();
    }

    private function getNorExpiredQueryBuilder(string $code): QueryBuilder
    {
        $qb = $this->createQueryBuilder('c');

        $from = (new DateTime())->sub(new DateInterval(self::LIFETIME));

        $qb
            ->where($qb->expr()->eq('c.code', ':code'))
            ->andWhere($qb->expr()->gt('c.createdAt', ':from'))
            ->setParameter('code', $code)
            ->setParameter('from', $from)
        ;

        return $qb;
    }
}
