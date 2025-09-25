<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 22.02.2025
 * Time: 13:40
 */

namespace TeleBot\Telegram\Command;

use TeleBot\Service\CodeGenerator;
use Xelbot\Telegram\Command\TelegramCommandInterface;
use Xelbot\Telegram\Command\TelegramCommandTrait;
use Xelbot\Telegram\Entity\Message;
use Xelbot\Telegram\Robot;

class Start implements TelegramCommandInterface
{
    use TelegramCommandTrait;

    public function __construct(private readonly CodeGenerator $codeGenerator)
    {
    }

    public function execute(Message $message): void
    {
        $tgUserId = $message->getFrom()->getId();

        $text = 'Приветствую тебя, человек ' . Robot::EMOJI_ROBOT;
        $text .= ' Я робот автоблокнота.';
        $text .= ' Если ты попал сюда не случайно, то знаешь, что делать.';
        $text .= "\n\nТвой код: " . $this->codeGenerator->generate($tgUserId);

        $this->requester->sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
