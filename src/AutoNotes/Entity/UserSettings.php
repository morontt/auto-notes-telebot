<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 01.03.2025
 * Time: 13:25
 */

namespace TeleBot\AutoNotes\Entity;

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
    private $id;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Car::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'RESTRICT')]
    private $defaultCar;

    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'RESTRICT')]
    private $defaultCurrency;

    #[ORM\ManyToOne(targetEntity: FuelType::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'RESTRICT')]
    private $defaultFuelType;
}
