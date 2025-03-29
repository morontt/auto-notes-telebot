<?php

/**
 * User: morontt
 * Date: 24.03.2025
 * Time: 09:44
 */

namespace TeleBot\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TeleBot\DTO\FuelDTO;
use TeleBot\Form\Type\RpcEntityType;
use TeleBot\Security\User;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;

class FuelForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('car', RpcEntityType::class, [
                'query_callback' => function (RpcUserRepository $rpcUserRepository, User $user) {
                    return $rpcUserRepository->getCars($user);
                },
            ])
            ->add('distance')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FuelDTO::class,
        ]);
    }
}
