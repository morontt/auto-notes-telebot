<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 01.05.2026
 * Time: 20:53
 */

namespace TeleBot\Form\Filters;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use TeleBot\Utils\GrpcReferense;

class ExpenseFilterForm extends BaseFilterForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => GrpcReferense::expenseTypeChoices(),
                'label' => 'form.label.expense_type',
                'choice_translation_domain' => false,
                'required' => false,
            ])
        ;

        parent::buildForm($builder, $options);
    }
}
