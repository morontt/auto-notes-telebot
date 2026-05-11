<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 10.05.2026
 * Time: 15:15
 */

namespace TeleBot\AutoNotes\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use TeleBot\AutoNotes\Entity\FuelType;

class FuelTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ft = new FuelType();
        $ft->setName('ДТ');

        $manager->persist($ft);

        $ft92 = new FuelType();
        $ft92->setName('АИ-92');

        $manager->persist($ft92);

        $ft95 = new FuelType();
        $ft95->setName('АИ-95');

        $manager->persist($ft95);

        $ft98 = new FuelType();
        $ft98->setName('АИ-98');

        $manager->persist($ft98);
        $manager->flush();

        $ftL = new FuelType();
        $ftL
            ->setName('ДТ-Л-К5')
            ->setParent($ft)
        ;
        $manager->persist($ftL);

        $ftE = new FuelType();
        $ftE
            ->setName('ДТ-Е-К5')
            ->setParent($ft)
        ;
        $manager->persist($ftE);

        $ftZ = new FuelType();
        $ftZ
            ->setName('ДТ-З-К5')
            ->setParent($ft)
        ;
        $manager->persist($ftZ);

        $manager->flush();
    }
}
