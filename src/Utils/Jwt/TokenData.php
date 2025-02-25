<?php

/**
 * User: morontt
 * Date: 25.02.2025
 * Time: 22:43
 */

namespace TeleBot\Utils\Jwt;

use DateTime;
use LogicException;

class TokenData
{
    public int $userId;
    public string $userName;
    public DateTime $issuedAt;
    public DateTime $expiredAt;

    public function __construct(array $data)
    {
        if (!isset($data['uid'], $data['uname'], $data['iat'], $data['exp'])) {
            throw new LogicException('Invalid access token');
        }

        $this->userId = $data['uid'];
        $this->userName = $data['uname'];

        $this->issuedAt = DateTime::createFromFormat('U', $data['iat']);
        $this->expiredAt = DateTime::createFromFormat('U', $data['exp']);
    }
}
