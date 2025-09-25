<?php
/**
 * User: morontt
 * Date: 22.02.2025
 * Time: 15:58
 */

declare(strict_types=1);

namespace TeleBot\Service;

use TeleBot\Entity\Code;
use TeleBot\Repository\CodeRepository;

class CodeGenerator
{
    private CodeRepository $repository;

    public function __construct(CodeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function generate(int $tgUserId): string
    {
        $random = $this->randomCode();

        $code = new Code();
        $code
            ->setTelegramUserId($tgUserId)
            ->setCode($random)
        ;

        $this->repository->save($code);

        return $random;
    }

    private function randomCode(): string
    {
        do {
            $random = (string)random_int(100000, 999999);
            $obj = $this->repository->getByNotExpiredCode($random);
        } while ($obj !== null);

        return $random;
    }
}
