<?php

/**
 * User: morontt
 * Date: 25.02.2025
 * Time: 22:07
 */

namespace TeleBot\Utils;

use LogicException;
use TeleBot\Utils\Jwt\TokenData;

class Jwt
{
    public static function claims(string $token): TokenData
    {
        $jwtParts = explode('.', $token);
        if (!isset($jwtParts[1])) {
            throw new LogicException('Invalid access token');
        }

        /** @var string|false $str */
        $str = base64_decode($jwtParts[1]);
        if ($str === false) {
            throw new LogicException('Invalid access token');
        }

        $claims = json_decode($str, true, 512, JSON_THROW_ON_ERROR);

        return new TokenData($claims);
    }
}
