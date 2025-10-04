<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 03.10.2025
 * Time: 22:42
 */

namespace TeleBot\DTO\List;

use TeleBot\DTO\ExpenseDTO;

/**
 * @extends BaseList<ExpenseDTO>
 */
class ExpenseDTOList extends BaseList
{
    public function supportedClass(): string
    {
        return ExpenseDTO::class;
    }
}
