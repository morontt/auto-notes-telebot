<?php

/**
 * User: morontt
 * Date: 23.03.2025
 * Time: 13:14
 */

namespace TeleBot\Service\RPC;

use TeleBot\Security\AccessTokenAwareInterface;
use Twirp\Context;

abstract class AbstractRepository
{
    /**
     * @return array<string, string>
     */
    protected function context(AccessTokenAwareInterface $user): array
    {
        return Context::withHttpRequestHeaders([], [
            'Authorization' => 'Bearer ' . $user->getAccessToken(),
        ]);
    }
}
