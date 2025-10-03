<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 08.02.2025
 * Time: 23:18
 */

namespace TeleBot\Security;

use LogicException;
use Symfony\Component\Security\Core\User\UserInterface;
use TeleBot\Utils\Jwt;

class User implements UserInterface, AccessTokenAwareInterface
{
    private string $username;
    private ?string $accessToken = null;

    public function __construct(string $username)
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

        return Jwt::claims($this->accessToken)->userId;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
    }
}
