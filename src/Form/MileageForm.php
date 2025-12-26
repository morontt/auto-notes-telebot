<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 26.12.2025
 */

namespace TeleBot\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TeleBot\DTO\MileageDTO;
use TeleBot\Form\Type\RpcEntityType;
use TeleBot\Security\User;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;

class MileageForm extends AbstractType
{
        public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('distance', IntegerType::class)
            ->add('car', RpcEntityType::class, [
                'query_callback' => function (RpcUserRepository $rpcUserRepository, User $user) {
                    return $rpcUserRepository->getCars($user);
                },
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MileageDTO::class,
        ]);
    }
}
