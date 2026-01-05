<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 04.01.2026
 */

namespace TeleBot\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Mileage extends Constraint
{
    public string $message = 'For mileage, the date and car are required';

    private string $dateField;

    public function __construct(
        ?string $dateField = null,
        ?array $groups = null,
        mixed $payload = null,
        mixed $options = [],
    ) {
        $dateField ??= $options['dateField'] ?? 'date';

        unset($options['dateField']);

        parent::__construct($options, $groups, $payload);

        $this->dateField = $dateField;
    }

    public function getDateField(): string
    {
        return $this->dateField;
    }
}
