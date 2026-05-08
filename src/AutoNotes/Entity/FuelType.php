<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 26.08.2025
 * Time: 23:07
 */

namespace TeleBot\AutoNotes\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table]
class FuelType
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null; // @phpstan-ignore property.unusedType

    #[ORM\Column(type: 'string', length: 16, unique: true)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: FuelType::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'RESTRICT')]
    private ?FuelType $parent;

    #[ORM\Column(type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParent(): ?FuelType
    {
        return $this->parent;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setCreatedAt(DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }
}
