<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 01.03.2025
 * Time: 13:25
 */

namespace TeleBot\AutoNotes\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use TeleBot\AutoNotes\Entity\Traits\TimeTrait;

#[ORM\Entity]
#[ORM\Table]
class UserSettings
{
    use TimeTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null; // @phpstan-ignore property.unusedType

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Car::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'RESTRICT')]
    private ?Car $defaultCar;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'RESTRICT')]
    private ?Currency $defaultCurrency;

    #[ORM\ManyToOne(targetEntity: FuelType::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'RESTRICT')]
    private ?FuelType $defaultFuelType;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDefaultCar(): ?Car
    {
        return $this->defaultCar;
    }

    public function setDefaultCar(?Car $defaultCar): static
    {
        $this->defaultCar = $defaultCar;

        return $this;
    }

    public function getDefaultCurrency(): ?Currency
    {
        return $this->defaultCurrency;
    }

    public function setDefaultCurrency(?Currency $defaultCurrency): static
    {
        $this->defaultCurrency = $defaultCurrency;

        return $this;
    }

    public function getDefaultFuelType(): ?FuelType
    {
        return $this->defaultFuelType;
    }

    public function setDefaultFuelType(?FuelType $defaultFuelType): static
    {
        $this->defaultFuelType = $defaultFuelType;

        return $this;
    }
}
