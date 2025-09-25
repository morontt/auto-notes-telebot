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

class OrderRepository extends AbstractRepository
{
    private OrderRepositoryClient $client;

    public function __construct(string $grpcUrl, private readonly LoggerInterface $logger)
    {
        $this->client = new OrderRepositoryClient($grpcUrl);
    }
}
