<?php

/**
 * User: morontt
 * Date: 22.02.2025
 * Time: 15:58
 */

namespace TeleBot\Service;

use Doctrine\ORM\EntityManagerInterface;
use TeleBot\Entity\Code;

class CodeGenerator
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function generate(int $tgUserId): string
    {
        $random = $this->randomCode();

        $code = new Code();
        $code
            ->setTelegramUserId($tgUserId)
            ->setCode($random)
        ;

        $this->em->persist($code);
        $this->em->flush();

        return $random;
    }

    private function randomCode(): string
    {
        return (string)random_int(100000, 999999);
    }
}
