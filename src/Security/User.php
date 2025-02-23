<?php

/**
 * User: morontt
 * Date: 08.02.2025
 * Time: 23:18
 */

namespace TeleBot\Security;

use LogicException;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private string $username;
    private ?string $accessToken;

    public function __construct($username)
    {
        $this->username = $username;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getUserId(): int
    {
        if ($this->accessToken === null) {
            throw new LogicException('User without access token');
        }

        $jwtParts = explode('.', $this->accessToken);
        if (!isset($jwtParts[1])) {
            throw new LogicException('Invalid access token');
        }

        $str = base64_decode($jwtParts[1]);
        if ($str === false) {
            throw new LogicException('Invalid access token');
        }

        $claims = json_decode($str, true, 512, JSON_THROW_ON_ERROR);
        if (!isset($claims['uid'])) {
            throw new LogicException('Invalid access token');
        }

        return $claims['uid'];
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
    }
}
