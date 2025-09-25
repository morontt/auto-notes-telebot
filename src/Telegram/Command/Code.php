<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 22.02.2025
 * Time: 17:47
 */

namespace TeleBot\Telegram\Command;

use TeleBot\Service\CodeGenerator;
use Xelbot\Telegram\Command\TelegramCommandInterface;
use Xelbot\Telegram\Command\TelegramCommandTrait;
use Xelbot\Telegram\Entity\Message;

class Code implements TelegramCommandInterface
{
    use TelegramCommandTrait;

    public function __construct(private readonly CodeGenerator $codeGenerator)
    {
    }

    public function execute(Message $message): void
    {
        $tgUserId = $message->getFrom()->getId();
        $text = 'Твой код: ' . $this->codeGenerator->generate($tgUserId);

        $this->requester->sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
