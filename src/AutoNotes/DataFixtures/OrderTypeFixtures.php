<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 10.05.2026
 * Time: 15:16
 */

namespace TeleBot\AutoNotes\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use TeleBot\AutoNotes\Entity\OrderType;

class OrderTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            'моторное масло',
            'трансмиссионное масло',
            'воздушный фильтр',
            'масляный фильтр',
            'топливный фильтр',
            'антифриз',
            'шины',
            'диски',
            'аккумулятор',
        ];

        foreach ($data as $item) {
            $ot = new OrderType();
            $ot->setName($item);

            $manager->persist($ot);
        }

        $manager->flush();
    }
}
