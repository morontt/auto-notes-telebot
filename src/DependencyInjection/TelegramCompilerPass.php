<?php

/**
 * User: morontt
 * Date: 22.02.2025
 * Time: 12:30
 */

namespace TeleBot\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TelegramCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('telebot.telegram_bot')) {
            return;
        }

        $definition = $container->findDefinition('telebot.telegram_bot');

        foreach ($container->findTaggedServiceIds('telegram-command') as $id => $tags) {
            $definition->addMethodCall(
                'addCommand',
                [new Reference($id)]
            );
        }
    }
}
