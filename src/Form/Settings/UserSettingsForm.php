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
use TeleBot\Form\Type\RpcEntityType;
use TeleBot\Security\User;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;

class UserSettingsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('defaultCar', RpcEntityType::class, [
                'query_callback' => function (RpcUserRepository $rpcUserRepository, User $user) {
                    return $rpcUserRepository->getCars($user);
                },
                'required' => false,
                'empty_data' => null,
            ])
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
