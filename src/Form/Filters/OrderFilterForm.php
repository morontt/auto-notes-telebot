<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 01.05.2026
 * Time: 20:58
 */

namespace TeleBot\Form\Filters;

use Symfony\Component\Form\FormBuilderInterface;

class OrderFilterForm extends BaseFilterForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
    }
}
