<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 13.09.2025
 * Time: 13:22
 */

namespace TeleBot\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use TeleBot\Exception\AuthorizationException;
use TeleBot\Exception\InvalidUserException;
use TeleBot\Security\AccessTokenAwareInterface;

class BaseController extends AbstractController
{
    protected function getAppUser(): AccessTokenAwareInterface
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AuthorizationException();
        }

        if (!$user instanceof AccessTokenAwareInterface) {
            throw new InvalidUserException(sprintf('User "%s" not supported', get_debug_type($user)));
        }

        return $user;
    }

    protected function offset(int $page, int $limit): int
    {
        return $page > 1 ? $limit * ($page - 1) : 0;
    }
}
