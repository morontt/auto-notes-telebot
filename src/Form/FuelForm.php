<?php declare(strict_types=1);
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
use TeleBot\Validator\Constraints\Mileage;

class FuelForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', TextType::class, [
                'label' => 'form.label.fuel_value',
            ])
            ->add('cost', CostType::class, [
                'label' => 'form.label.cost',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'form.label.date',
            ])
            ->add('car', RpcEntityType::class, [
                'query_callback' => function (RpcUserRepository $rpcUserRepository, User $user) {
                    return $rpcUserRepository->getCars($user);
                },
                'label' => 'form.label.car',
                'required' => false,
            ])
            ->add('station', RpcEntityType::class, [
                'query_fuel_callback' => function (RpcFuelRepository $rpcUserRepository, User $user) {
                    return $rpcUserRepository->getFillingStations($user);
                },
                'label' => 'form.label.filling_station',
            ])
            ->add('type', RpcEntityType::class, [
                'query_fuel_callback' => function (RpcFuelRepository $rpcUserRepository, User $user) {
                    return $rpcUserRepository->getFuelTypes($user);
                },
                'label' => 'form.label.fuel_type',
            ])
            ->add('distance', IntegerType::class, [
                'required' => false,
                'label' => 'form.label.distance',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'form.submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FuelDTO::class,
            'constraints' => new Mileage(),
        ]);
    }
}
