<?php

/**
 * User: morontt
 * Date: 13.03.2025
 * Time: 09:54
 */

namespace TeleBot\Form\Type;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TeleBot\Form\ChoiceList\RpcRepositoryChoiceLoader;
use TeleBot\Service\RPC\UserRepository;

class RpcEntityType extends AbstractType
{
    public function __construct(
        private readonly UserRepository $rpcUserRepository,
        private readonly Security $security
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $choiceLoader = function (Options $options): ChoiceLoaderInterface {
            $repo = $this->rpcUserRepository;
            $user = $this->security->getUser();

            return ChoiceList::loader(
                $this,
                new RpcRepositoryChoiceLoader($options['query_callback'], $repo, $user),
                [$options['query_callback']]
            );
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
            'choices' => null,
            'choice_loader' => $choiceLoader,
            'choice_label' => ChoiceList::label($this, [__CLASS__, 'createChoiceLabel']),
            'choice_value' => $choiceValue,
            'choice_translation_domain' => false,
        ]);

        $resolver->setRequired(['query_callback']);
        $resolver->setAllowedTypes('query_callback', ['callable']);
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
