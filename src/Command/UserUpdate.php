<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 16.05.2024
 * Time: 16:44
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

#[AsCommand(name: 'telebot:user-update')]
class UserUpdate extends Command
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
            ->setDescription('Update the user.')
            ->setHelp('Update user password by username')
            ->addArgument('username', InputArgument::REQUIRED, 'User name')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $output->writeln('');

        /** @var \TeleBot\AutoNotes\Repository\UserRepository */
        $userRepo = $this->em->getRepository(User::class);

        $user = $userRepo->findOneBy(['username' => $username]);
        if (!$user) {
            $output->writeln(sprintf('<error>Error: user "%s" not found</error>', $username));
        } else {
            $user->setPassword($this->passwordHasher()->hash($password));
            $this->em->flush();

            $output->writeln(sprintf('<info>Update user: <comment>%s</comment></info>', $username));
        }
        $output->writeln('');

        return Command::SUCCESS;
    }
}
