<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 15.05.2024
 * Time: 12:22
 */

namespace TeleBot\AutoNotes\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use TeleBot\AutoNotes\Entity\Traits\TimeTrait;

#[ORM\Entity]
#[ORM\Table]
class Car
{
    use TimeTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null; // @phpstan-ignore property.unusedType

    #[ORM\Column(type: 'string')]
    private string $brandName;

    #[ORM\Column(type: 'string')]
    private string $modelName;

    #[ORM\Column(name: 'prod_year', type: 'integer', nullable: true)]
    private ?int $yearOfProduction;

    #[ORM\Column(type: 'string', length: 17, nullable: true)]
    private ?string $vin;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private User $user;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrandName(): string
    {
        return $this->brandName;
    }

    public function setBrandName(string $brandName): static
    {
        $this->brandName = $brandName;

        return $this;
    }

    public function getModelName(): string
    {
        return $this->modelName;
    }

    public function setModelName(string $modelName): static
    {
        $this->modelName = $modelName;

        return $this;
    }

    public function getYearOfProduction(): ?int
    {
        return $this->yearOfProduction;
    }

    public function setYearOfProduction(?int $yearOfProduction): static
    {
        $this->yearOfProduction = $yearOfProduction;

        return $this;
    }

    public function getVin(): ?string
    {
        return $this->vin;
    }

    public function setVin(?string $vin): static
    {
        $this->vin = $vin;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
