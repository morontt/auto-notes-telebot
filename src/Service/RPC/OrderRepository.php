<?php
/**
 * User: morontt
 * Date: 25.09.2025
 * Time: 08:14
 */

declare(strict_types=1);

namespace TeleBot\Service\RPC;

use AutoNotes\Server\OrderRepositoryClient;
use Psr\Log\LoggerInterface;
use TeleBot\LogTrait;

class OrderRepository extends AbstractRepository
{
    use LogTrait;

    private OrderRepositoryClient $client;

    public function __construct(string $grpcUrl, LoggerInterface $logger)
    {
        $this->client = new OrderRepositoryClient($grpcUrl);

        $this->setLogger($logger);
    }
}
