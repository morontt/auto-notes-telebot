<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 10.05.2026
 * Time: 12:48
 */

namespace TeleBot\AutoNotes\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use TeleBot\AutoNotes\Entity\User;
use TeleBot\Security\Traits\PasswordTrait;

class UserFixtures extends Fixture
{
    use PasswordTrait;

    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $user
            ->setUsername('admin')
            ->setPassword($this->passwordHasher()->hash('test'))
        ;

        $manager->persist($user);
        $manager->flush();

        $this->addReference('admin', $user);
    }
}
