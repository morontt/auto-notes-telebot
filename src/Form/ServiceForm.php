<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 24.01.2026
 */

namespace TeleBot\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use TeleBot\DTO\ServiceDTO;
use TeleBot\Form\Type\CostType;
use TeleBot\Form\Type\RpcEntityType;
use TeleBot\Security\User;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;
use TeleBot\Validator\Constraints\Mileage;

class ServiceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => 'form.label.description',
                'required' => true,
                'attr' => [
                    'rows' => 5,
                    'cols' => 25,
                ],
                'constraints' => [
                    new Constraints\NotBlank(),
                    new Constraints\Length(max: 255)
                ],
            ])
            ->add('cost', CostType::class, [
                'label' => 'form.label.cost',
                'required' => false,
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
            ])
            ->add('distance', IntegerType::class, [
                'label' => 'form.label.distance',
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
            'data_class' => ServiceDTO::class,
            'constraints' => new Mileage(),
        ]);
    }
}
