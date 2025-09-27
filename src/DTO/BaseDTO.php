<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 27.09.2025
 * Time: 09:50
 */

namespace TeleBot\DTO;

class BaseDTO
{
    public function toArray(): array
    {
        return $this->doToArray(get_object_vars($this));
    }

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
