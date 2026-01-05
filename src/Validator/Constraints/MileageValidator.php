<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 04.01.2026
 */

namespace TeleBot\Validator\Constraints;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class MileageValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Mileage) {
            throw new UnexpectedTypeException($constraint, Mileage::class);
        }

        if (!is_object($value)) {
            return;
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $distance = $propertyAccessor->getValue($value, 'distance');
        $car = $propertyAccessor->getValue($value, 'car');
        $date = $propertyAccessor->getValue($value, $constraint->getDateField());

        if ($distance && (!$car || !$date)) {
            $this->context->addViolation($constraint->message);
        }
    }
}
