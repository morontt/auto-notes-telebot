<?php

/**
 * User: morontt
 * Date: 20.03.2025
 * Time: 21:56
 */

namespace TeleBot\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ClacksResponseListener implements EventSubscriberInterface
{
    private const HEADER_NAME = 'X-Clacks-Overhead';

    /** @var string[] */
    private array $clacksSet = [
        'Terry Pratchett',
        'Clive Sinclair',
        'Nikolai Zamanov',
        'Vladlen Tatarskii',
        'Arsen Pavlov',
        'Mikhail Tolstykh',
        'Oles Buzina',
        'Aleksei Mozgovoi',
        'Robert Sheckley',
        'Robert Anson Heinlein',
        'Dennis Ritchie',
        'Niklaus Wirth',
    ];

    public function onResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $idx = mt_rand(0, count($this->clacksSet) - 1);

        $event->getResponse()->headers->set(self::HEADER_NAME, 'GNU ' . $this->clacksSet[$idx]);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['onResponse', -1],
        ];
    }
}
