<?php

/**
 * User: morontt
 * Date: 28.06.2025
 * Time: 07:15
 */

namespace TeleBot\Telegram\Command;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use TeleBot\Entity\RegionCode;
use Xelbot\Telegram\Command\TelegramCommandInterface;
use Xelbot\Telegram\Command\TelegramCommandTrait;
use Xelbot\Telegram\Entity\Message;

class Region implements TelegramCommandInterface
{
    use TelegramCommandTrait;

    /**
     * @var EntityRepository<RegionCode> $repository
     */
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(RegionCode::class);
    }

    public function execute(Message $message): void
    {
        $code = null;
        $matches = [];
        if (preg_match('/^\/region (\d+)$/', $message->getText(), $matches)) {
            $code = $matches[1];
        }

        if ($code) {
            $regions = $this->findRegions($code);
            if (count($regions)) {
                $text = sprintf("Регион %s:\n\n%s", $code, implode("\n", $regions));
            } else {
                $text = sprintf("Регион %s:\n\n%s", $code, 'Ничего не нашлось &#x1F937;&#x200D;&#x2642;&#xFE0F;');
            }

            $this->requester->sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'text' => $text,
                'parse_mode' => 'HTML',
            ]);
        } else {
            $this->requester->sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'text' => "Необходимо указать код региона\n\nНапример:\n/region 82",
                'parse_mode' => 'HTML',
            ]);
        }
    }

    /**
     * @return string[]
     */
    private function findRegions(string $code): array
    {
        $regionCodes = $this->repository->findBy(['code' => $code]);

        $result = [];
        foreach ($regionCodes as $regionCode) {
            $result[] = $regionCode->getRegion()->getName();
        }

        return $result;
    }
}
