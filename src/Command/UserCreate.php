<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 15.05.2024
 * Time: 20:36
 */

namespace TeleBot\Command;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TeleBot\AutoNotes\Entity\User;
use TeleBot\Security\Traits\PasswordTrait;

#[AsCommand(name: 'telebot:user-create')]
class UserCreate extends Command
{
    use PasswordTrait;

    private ObjectManager $em;

    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct();

        $this->em = $doctrine->getManager('main');
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...')
            ->addArgument('username', InputArgument::REQUIRED, 'User name')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $user = new User();
        $user
            ->setUsername($username)
            ->setPassword($this->passwordHasher()->hash($password))
        ;

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('');
        $output->writeln(sprintf('<info>Create user: <comment>%s</comment></info>', $username));
        $output->writeln('');

        return Command::SUCCESS;
    }
}
