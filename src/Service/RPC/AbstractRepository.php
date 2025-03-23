<?php

/**
 * User: morontt
 * Date: 23.03.2025
 * Time: 13:14
 */

namespace TeleBot\Service\RPC;

use TeleBot\Security\User;
use Twirp\Context;

abstract class AbstractRepository
{
    protected function context(User $user): array
    {
        return Context::withHttpRequestHeaders([], [
            'Authorization' => 'Bearer ' . $user->getAccessToken(),
        ]);
    }
}
