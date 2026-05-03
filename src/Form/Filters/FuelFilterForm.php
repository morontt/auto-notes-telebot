<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 01.05.2026
 * Time: 08:52
 */

namespace TeleBot\Form\Filters;

use Symfony\Component\Form\FormBuilderInterface;
use TeleBot\Form\Type\RpcEntityType;
use TeleBot\Security\User;
use TeleBot\Service\RPC\FuelRepository as RpcFuelRepository;

class FuelFilterForm extends BaseFilterForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('station', RpcEntityType::class, [
                'query_fuel_callback' => function (RpcFuelRepository $rpcUserRepository, User $user) {
                    return $rpcUserRepository->getFillingStations($user);
                },
                'label' => 'form.label.filling_station',
                'required' => false,
            ])
            ->add('type', RpcEntityType::class, [
                'query_fuel_callback' => function (RpcFuelRepository $rpcUserRepository, User $user) {
                    return $rpcUserRepository->getFuelTypes($user);
                },
                'label' => 'form.label.fuel_type',
                'required' => false,
            ])
        ;

        parent::buildForm($builder, $options);
    }
}
