<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 11.05.2026
 * Time: 20:30
 */

namespace TeleBot\AutoNotes\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use TeleBot\AutoNotes\Entity\FillingStation;

class FillingStationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            'Atan',
            'TES',
            'Lukoil',
            'Роснефть',
            'TATneft',
        ];

        foreach ($data as $item) {
            $fs = new FillingStation();
            $fs->setName($item);

            $manager->persist($fs);
        }

        $manager->flush();
    }
}
