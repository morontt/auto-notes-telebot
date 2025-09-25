<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 16.02.2025
 * Time: 21:48
 */

namespace TeleBot\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;
use TeleBot\Entity\Trait\TimeTrait;
use TeleBot\Repository\CodeRepository;

#[ORM\Entity(repositoryClass: CodeRepository::class)]
#[ORM\Table]
class Code
{
    use TimeTrait;

    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $userId;

    #[ORM\Column(type: 'bigint')]
    private $telegramUserId;

    #[ORM\Column(type: 'string', length: 6)]
    private $code;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getTelegramUserId(): int
    {
        return $this->telegramUserId;
    }

    public function setTelegramUserId($telegramUserId): self
    {
        $this->telegramUserId = $telegramUserId;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
