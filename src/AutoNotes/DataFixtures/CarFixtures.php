<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 11.05.2026
 * Time: 20:34
 */

namespace TeleBot\AutoNotes\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use TeleBot\AutoNotes\Entity\Car;
use TeleBot\AutoNotes\Entity\User;

class CarFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $car = new Car();
        $car
            ->setBrandName('Nissan')
            ->setModelName('Patrol')
            ->setVin('1HGBH42JXMN199171')
            ->setYearOfProduction(1994)
            ->setUser($this->getReference('admin', User::class))
        ;
        $manager->persist($car);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
