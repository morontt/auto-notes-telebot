<?php

/**
 * User: morontt
 * Date: 13.03.2025
 * Time: 09:54
 */

namespace TeleBot\Form\Type;

use LogicException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TeleBot\Form\ChoiceList\RpcRepositoryChoiceLoader;
use TeleBot\Security\AccessTokenAwareInterface;
use TeleBot\Service\RPC\FuelRepository;
use TeleBot\Service\RPC\UserRepository;

class RpcEntityType extends AbstractType
{
    public function __construct(
        private readonly UserRepository $rpcUserRepository,
        private readonly FuelRepository $rpcFuelRepository,
        private readonly Security $security
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $choiceLoader = function (Options $options): ChoiceLoaderInterface {
            $userRepo = $this->rpcUserRepository;
            $fuelRepo = $this->rpcFuelRepository;
            $user = $this->security->getUser();
            if (!$user instanceof AccessTokenAwareInterface) {
                throw new LogicException(sprintf('User "%s" not supported', get_debug_type($user)));
            }

            if (is_callable($options['query_callback'])) {
                $loader = ChoiceList::loader(
                    $this,
                    new RpcRepositoryChoiceLoader($options['query_callback'], $userRepo, $user),
                    [$options['query_callback']]
                );
            } elseif (is_callable($options['query_fuel_callback'])) {
                $loader = ChoiceList::loader(
                    $this,
                    new RpcRepositoryChoiceLoader($options['query_fuel_callback'], $fuelRepo, $user),
                    [$options['query_fuel_callback']]
                );
            } else {
                throw new LogicException('query_callback or query_fuel_callback must be defined');
            }

            return $loader;
        };

        $choiceValue = function (Options $options) {
            return ChoiceList::value($this, static function ($obj) {
                if ($obj) {
                    return $obj->getId();
                }

                return null;
            });
        };

        $resolver->setDefaults([
            'query_callback' => null,
            'query_fuel_callback' => null,
            'choices' => null,
            'choice_loader' => $choiceLoader,
            'choice_label' => ChoiceList::label($this, [__CLASS__, 'createChoiceLabel']),
            'choice_value' => $choiceValue,
            'choice_translation_domain' => false,
        ]);

        $resolver->setAllowedTypes('query_callback', ['null', 'callable']);
        $resolver->setAllowedTypes('query_fuel_callback', ['null', 'callable']);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public static function createChoiceLabel(object $choice): string
    {
        return (string) $choice;
    }
}
