<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 26.02.2025
 * Time: 09:43
 */

namespace TeleBot\Entity\Trait;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait TimeTrait
{
    #[ORM\Column(type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $updatedAt = null;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
