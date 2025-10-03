<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 03.10.2025
 * Time: 08:09
 */

namespace TeleBot\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use TeleBot\Entity\LoginHistory;
use TeleBot\Security\User;

readonly class LoginHistoryListener implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function onSuccessfulLogin(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        if ($user instanceof User) {
            $id = $user->getUserId();

            $loginEvent = new LoginHistory();
            $loginEvent->setUserId($id);

            $this->em->persist($loginEvent);
            $this->em->flush();
        }
    }

    public static function getSubscribedEvents()
    {
        return [LoginSuccessEvent::class => 'onSuccessfulLogin'];
    }
}
