<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 10.05.2026
 * Time: 14:02
 */

namespace TeleBot\AutoNotes\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use TeleBot\AutoNotes\Entity\Currency;

class CurrencyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $rub = new Currency();
        $rub
            ->setName('Рубль')
            ->setCode('RUB')
        ;

        $manager->persist($rub);
        $manager->flush();

        $uah = new Currency();
        $uah
            ->setName('Гривна')
            ->setCode('UAH')
        ;

        $manager->persist($uah);
        $manager->flush();
    }
}
