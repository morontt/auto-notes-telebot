<?php

/**
 * User: morontt
 * Date: 12.03.2025
 * Time: 21:42
 */

namespace TeleBot\Form\Settings;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TeleBot\DTO\UserSettingsDTO;

class UserSettingsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('defaultCar')
            ->add('defaultCurrency')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserSettingsDTO::class,
        ]);
    }
}
