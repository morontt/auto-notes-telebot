<?php

/**
 * User: morontt
 * Date: 26.02.2025
 * Time: 09:32
 */

namespace TeleBot\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use TeleBot\Security\User;
use TeleBot\Service\TokenStorage;

class SaveTokenListener implements EventSubscriberInterface
{
    private TokenStorage $storage;

    public function __construct(TokenStorage $storage)
    {
        $this->storage = $storage;
    }

    public function onSuccessfulLogin(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        if ($user instanceof User) {
            $id = $user->getUserId();

            $this->storage->setToken($user->getAccessToken(), $id);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class => 'onSuccessfulLogin'];
    }
}
