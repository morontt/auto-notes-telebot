<?php

/**
 * User: morontt
 * Date: 26.02.2025
 * Time: 09:42
 */

namespace TeleBot\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;
use TeleBot\Entity\Trait\TimeTrait;
use TeleBot\Repository\AccessTokenRepository;

#[ORM\Entity(repositoryClass: AccessTokenRepository::class)]
#[ORM\Table]
class AccessToken
{
    use TimeTrait;

    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private $id;

    #[ORM\Column(type: 'integer', unique: true)]
    private $userId;

    #[ORM\Column(type: Types::TEXT, length: 65000)]
    private $token;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private $expiredAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId($userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken($token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getExpiredAt(): DateTime
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(DateTime $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }
}
