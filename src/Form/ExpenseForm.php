<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 06.10.2025
 * Time: 20:47
 */

namespace TeleBot\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TeleBot\DTO\ExpenseDTO;
use TeleBot\Form\Type\CostType;
use TeleBot\Form\Type\RpcEntityType;
use TeleBot\Security\User;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;
use TeleBot\Utils\GrpcReferense;

class ExpenseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'label' => 'form.label.description',
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
            ->add('type', ChoiceType::class, [
                'choices' => GrpcReferense::expenseTypeChoices(),
                'label' => 'form.label.expense_type',
                'choice_translation_domain' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'form.submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExpenseDTO::class,
        ]);
    }
}
