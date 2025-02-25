<?php

/**
 * User: morontt
 * Date: 25.02.2025
 * Time: 09:51
 */

namespace TeleBot\Form\Settings;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CodeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'required' => true,
                'label' => 'Код',
            ])
            ->add('submit', SubmitType::class)
        ;
    }
}
