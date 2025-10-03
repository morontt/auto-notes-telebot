<?php declare(strict_types=1);
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
    private ?Ulid $id;

    #[ORM\Column(type: 'integer', unique: true)]
    private int $userId;

    #[ORM\Column(type: Types::TEXT, length: 65000)]
    private string $token;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private DateTime $expiredAt;

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

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
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
