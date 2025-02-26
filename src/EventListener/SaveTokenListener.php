<?php

/**
 * User: morontt
 * Date: 26.02.2025
 * Time: 09:32
 */

namespace TeleBot\EventListener;

use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use TeleBot\Entity\AccessToken;
use TeleBot\Repository\AccessTokenRepository;
use TeleBot\Security\User;

class SaveTokenListener implements EventSubscriberInterface
{
    private AccessTokenRepository $repository;

    public function __construct(AccessTokenRepository $repository)
    {
        $this->repository = $repository;
    }

    public function onSuccessfulLogin(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        if ($user instanceof User) {
            $id = $user->getUserId();

            /* @var AccessToken $tokenObj */
            $tokenObj = $this->repository->findOneBy(['userId' => $id]);
            if ($tokenObj) {
                $tokenObj
                    ->setToken($user->getAccessToken())
                    ->setUpdatedAt(new DateTime())
                ;
            } else {
                $tokenObj = new AccessToken();
                $tokenObj
                    ->setToken($user->getAccessToken())
                    ->setUserId($id)
                ;
            }

            $this->repository->save($tokenObj);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class => 'onSuccessfulLogin'];
    }
}
