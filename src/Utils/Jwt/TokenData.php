<?php

/**
 * User: morontt
 * Date: 25.02.2025
 * Time: 22:43
 */

namespace TeleBot\Utils\Jwt;

use DateException;
use DateTime;
use DateTimeZone;
use LogicException;

class TokenData
{
    public int $userId;
    public string $userName;
    public DateTime $issuedAt;
    public DateTime $expiredAt;

    /**
     * @param array<string, string> $data
     */
    public function __construct(array $data)
    {
        if (!isset($data['uid'], $data['uname'], $data['iat'], $data['exp'])) {
            throw new LogicException('Invalid access token');
        }

        // @phpstan-ignore assign.propertyType
        $this->userId = $data['uid'];
        $this->userName = $data['uname'];

        try {
            $tz = new DateTimeZone(date_default_timezone_get());
        } catch (DateException $e) {
            $tz = new DateTimeZone('UTC');
        }

        $this->issuedAt = DateTime::createFromFormat('U', $data['iat'])->setTimezone($tz);
        $this->expiredAt = DateTime::createFromFormat('U', $data['exp'])->setTimezone($tz);
    }
}
