<?php
/**
 * User: morontt
 * Date: 25.09.2025
 * Time: 08:14
 */

declare(strict_types=1);

namespace TeleBot\Service\RPC;

use AutoNotes\Server\OrderFilter;
use AutoNotes\Server\OrderRepositoryClient;
use Psr\Log\LoggerInterface;
use TeleBot\DTO\List\OrderDTOList;
use TeleBot\DTO\OrderDTO;
use TeleBot\LogTrait;
use TeleBot\Security\AccessTokenAwareInterface;

class OrderRepository extends AbstractRepository
{
    use LogTrait;

    private OrderRepositoryClient $client;

    public function __construct(string $grpcUrl, LoggerInterface $logger)
    {
        $this->client = new OrderRepositoryClient($grpcUrl);

        $this->setLogger($logger);
    }

    /**
     * @throws \Twirp\Error
     */
    public function getOrders(AccessTokenAwareInterface $user, OrderFilter $filter): OrderDTOList
    {
        $response = $this->client->GetOrders($this->context($user), $filter);

        $current = $response->getMeta()?->getCurrent() ?? 1;
        $last = $response->getMeta()?->getLast() ?? 1;

        $orders = new OrderDTOList($current, $last);
        // @phpstan-ignore foreach.nonIterable
        foreach ($response->getOrders() as $item) {
            $orders->add(OrderDTO::fromData($item));
        }

        $this->debug('gRPC response', ['orders_cnt' => count($orders)]);

        return $orders;
    }
}
