<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 28.06.2025
 * Time: 09:59
 */

namespace TeleBot\Security;

interface AccessTokenAwareInterface
{
    public function getAccessToken(): ?string;

    public function getUserId(): int;
}
