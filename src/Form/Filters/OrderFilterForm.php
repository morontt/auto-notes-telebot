<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 01.05.2026
 * Time: 20:58
 */

namespace TeleBot\Form\Filters;

use Symfony\Component\Form\FormBuilderInterface;
use TeleBot\Form\Type\RpcEntityType;
use TeleBot\Security\User;
use TeleBot\Service\RPC\OrderRepository as RpcOrderRepository;

class OrderFilterForm extends BaseFilterForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', RpcEntityType::class, [
                'query_order_callback' => function (RpcOrderRepository $rpcOrderRepository, User $user) {
                    return $rpcOrderRepository->getOrderTypes($user);
                },
                'label' => 'form.label.order_type',
                'required' => false,
            ])
        ;

        parent::buildForm($builder, $options);
    }
}
