<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 15.05.2024
 * Time: 12:22
 */

namespace TeleBot\AutoNotes\Entity;

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
    private $id;

    #[ORM\Column(type: 'string')]
    private $brandName;

    #[ORM\Column(type: 'string')]
    private $modelName;

    #[ORM\Column(name: 'prod_year', type: 'integer', nullable: true)]
    private $yearOfProduction;

    #[ORM\Column(type: 'string', length: 17, nullable: true)]
    private $vin;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private $user;
}
