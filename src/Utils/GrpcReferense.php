<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 04.10.2025
 * Time: 08:24
 */

namespace TeleBot\Utils;

use AutoNotes\Server\ExpenseType;

class GrpcReferense
{
    /** @var array<int, string> */
    public static array $expenseTypeTitle = [
        ExpenseType::GARAGE    => 'Гараж',
        ExpenseType::TOOLS     => 'Инструменты',
        ExpenseType::TAX       => 'Налоги',
        ExpenseType::INSURANCE => 'Страховка',
        ExpenseType::ROAD      => 'Дорога',
        ExpenseType::WASHING   => 'Мойка',
        ExpenseType::PARKING   => 'Парковка',
        ExpenseType::OTHER     => 'Разное',
    ];

    /**
     * @return array<string, int>
     */
    public static function expenseTypeChoices(): array
    {
        $choices = [];
        foreach (self::$expenseTypeTitle as $key => $value) {
            $choices[$value] = $key;
        }
        ksort($choices);

        return $choices;
    }
}
