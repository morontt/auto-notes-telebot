<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 27.09.2025
 * Time: 09:50
 */

namespace TeleBot\DTO;

use DateInterval;
use DateTime;
use Google\Protobuf\Timestamp;

class BaseDTO
{
    /**
     * @phpstan-ignore missingType.iterableValue
     */
    public function toArray(): array
    {
        return $this->doToArray(get_object_vars($this));
    }

    public static function fromPbTimestamp(Timestamp $ts): DateTime
    {
        return $ts->toDateTime()->add(new DateInterval('PT12H'));
    }

    public static function toPbTimestamp(DateTime $dt): Timestamp
    {
        $ts = new Timestamp();
        $date = clone $dt;
        $ts->fromDateTime($date->add(new DateInterval('PT12H')));

        return $ts;
    }

    /**
     * @phpstan-ignore missingType.iterableValue, missingType.iterableValue
     */
    private function doToArray(array $objVars): array
    {
        $result = [];

        foreach ($objVars as $objVarKey => $objVarValue) {
            if ($objVarValue instanceof self) {
                $result[$objVarKey] = $objVarValue->toArray();
            } elseif (\is_array($objVarValue)) {
                $result[$objVarKey] = $this->doToArray($objVarValue);
            } elseif ($objVarValue !== null) {
                $result[$objVarKey] = $objVarValue;
            }
        }

        return $result;
    }
}
