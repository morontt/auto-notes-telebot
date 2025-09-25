<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 22.02.2025
 * Time: 10:44
 */

namespace TeleBot\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdatesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('data', TextareaType::class, [
                'required' => true,
                'label' => 'JSON',
                'attr' => [
                    'rows' => 10,
                    'cols' => 50,
                ],
            ])
            ->add('submit', SubmitType::class)
        ;
    }
}
