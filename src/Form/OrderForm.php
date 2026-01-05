<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 08.10.2025
 * Time: 09:42
 */

namespace TeleBot\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TeleBot\DTO\OrderDTO;
use TeleBot\Form\Type\CostType;
use TeleBot\Form\Type\RpcEntityType;
use TeleBot\Security\User;
use TeleBot\Service\RPC\OrderRepository as RpcOrderRepository;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;
use TeleBot\Validator\Constraints\Mileage;

class OrderForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'label' => 'form.label.description',
            ])
            ->add('type', RpcEntityType::class, [
                'query_order_callback' => function (RpcOrderRepository $rpcOrderRepository, User $user) {
                    return $rpcOrderRepository->getOrderTypes($user);
                },
                'label' => 'form.label.order_type',
            ])
            ->add('cost', CostType::class, [
                'label' => 'form.label.cost',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'form.label.date',
            ])
            ->add('usedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'form.label.used_at',
                'required' => false,
            ])
            ->add('car', RpcEntityType::class, [
                'query_callback' => function (RpcUserRepository $rpcUserRepository, User $user) {
                    return $rpcUserRepository->getCars($user);
                },
                'label' => 'form.label.car',
                'required' => false,
            ])
            ->add('distance', IntegerType::class, [
                'label' => 'form.label.distance',
                'required' => false,
            ])
            ->add('capacity', TextType::class, [
                'label' => 'form.label.capacity',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'form.submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderDTO::class,
            'constraints' => new Mileage(dateField: 'usedAt'),
        ]);
    }
}
