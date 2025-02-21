<?php

namespace TeleBot\Command\Telegram;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebhookCommand extends AbstractTelegramCommand
{
    protected function configure(): void
    {
        $this
            ->setName('telebot:webhook')
            ->setDescription('Set webhook for telegram bot')
            ->addArgument('url', InputArgument::REQUIRED, 'Webhook URL')
            ->addArgument('certificate', InputArgument::OPTIONAL, 'Path to certificate')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \Xelbot\Telegram\Exception\TelegramException
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->bot->setWebhook($input->getArgument('url'), $input->getArgument('certificate'));
        if ($result->isOk()) {
            $output->writeln($result->getDescription());
        }

        return 0;
    }
}
