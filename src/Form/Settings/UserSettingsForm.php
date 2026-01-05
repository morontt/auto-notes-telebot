<?php declare(strict_types=1);

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
use TeleBot\Service\RPC\FuelRepository as RpcFuelRepository;
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
                'label' => 'form.label.default_car',
                'required' => false,
                'empty_data' => null,
            ])
            ->add('defaultCurrency', RpcEntityType::class, [
                'query_callback' => function (RpcUserRepository $rpcUserRepository, User $user) {
                    return $rpcUserRepository->getCurrencies($user);
                },
                'label' => 'form.label.default_currency',
                'required' => false,
                'empty_data' => null,
            ])
            ->add('defaultFuelType', RpcEntityType::class, [
                'query_fuel_callback' => function (RpcFuelRepository $rpcUserRepository, User $user) {
                    return $rpcUserRepository->getFuelTypes($user);
                },
                'label' => 'form.label.default_fuel',
                'required' => false,
                'empty_data' => null,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'form.submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserSettingsDTO::class,
        ]);
    }
}
