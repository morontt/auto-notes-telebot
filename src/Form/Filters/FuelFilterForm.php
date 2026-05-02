<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 01.05.2026
 * Time: 08:52
 */

namespace TeleBot\Form\Filters;

use Symfony\Component\Form\FormBuilderInterface;

class FuelFilterForm extends BaseFilterForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
    }
}
