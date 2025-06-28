<?php

/**
 * User: morontt
 * Date: 29.03.2025
 * Time: 12:58
 */

namespace TeleBot\Form\Type;

use LogicException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TeleBot\DTO\CostDTO;
use TeleBot\Security\AccessTokenAwareInterface;
use TeleBot\Service\RPC\UserRepository;

class CostType extends AbstractType
{
    public function __construct(
        private readonly UserRepository $rpcUserRepository,
        private readonly Security $security
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        if (!$user instanceof AccessTokenAwareInterface) {
            throw new LogicException(sprintf('User "%s" not supported', get_debug_type($user)));
        }

        $choices = [];
        foreach ($this->rpcUserRepository->getCurrencies($user) as $currency) {
            $choices[(string)$currency] = $currency->getCode();
        }

        $builder
            ->add('value', TextType::class)
            ->add('currencyCode', ChoiceType::class, [
                'choices' => $choices,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CostDTO::class,
        ]);
    }
}
