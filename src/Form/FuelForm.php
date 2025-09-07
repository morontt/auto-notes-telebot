<?php

/**
 * User: morontt
 * Date: 24.03.2025
 * Time: 09:44
 */

namespace TeleBot\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TeleBot\DTO\FuelDTO;
use TeleBot\Form\Type\CostType;
use TeleBot\Form\Type\RpcEntityType;
use TeleBot\Security\User;
use TeleBot\Service\RPC\FuelRepository as RpcFuelRepository;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;

class FuelForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', TextType::class)
            ->add('cost', CostType::class)
            ->add('date', DateType::class)
            ->add('car', RpcEntityType::class, [
                'required' => false,
                'query_callback' => function (RpcUserRepository $rpcUserRepository, User $user) {
                    return $rpcUserRepository->getCars($user);
                },
            ])
            ->add('station', RpcEntityType::class, [
                'query_fuel_callback' => function (RpcFuelRepository $rpcUserRepository, User $user) {
                    return $rpcUserRepository->getFillingStations($user);
                },
            ])
            ->add('type', RpcEntityType::class, [
                'query_fuel_callback' => function (RpcFuelRepository $rpcUserRepository, User $user) {
                    return $rpcUserRepository->getFuelTypes($user);
                },
            ])
            ->add('distance', IntegerType::class, [
                'required' => false,
            ])
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
