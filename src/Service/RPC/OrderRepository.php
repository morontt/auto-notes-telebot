<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 25.09.2025
 * Time: 08:14
 */

namespace TeleBot\Service\RPC;

use AutoNotes\Server\ExpenseFilter;
use AutoNotes\Server\IdRequest;
use AutoNotes\Server\OrderFilter;
use AutoNotes\Server\OrderRepositoryClient;
use Google\Protobuf\GPBEmpty;
use Psr\Log\LoggerInterface;
use TeleBot\DTO\ExpenseDTO;
use TeleBot\DTO\List\ExpenseDTOList;
use TeleBot\DTO\List\OrderDTOList;
use TeleBot\DTO\List\OrderTypeDTOList;
use TeleBot\DTO\OrderDTO;
use TeleBot\DTO\OrderTypeDTO;
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

    /**
     * @throws \Twirp\Error
     */
    public function findOrder(AccessTokenAwareInterface $user, int $id): OrderDTO
    {
        $req = new IdRequest();
        $req->setId($id);

        $response = $this->client->FindOrder($this->context($user), $req);
        $this->debug('gRPC response', ['order_id' => $response->getId()]);

        return OrderDTO::fromData($response);
    }

    /**
     * @throws \Twirp\Error
     */
    public function saveOrder(AccessTokenAwareInterface $user, OrderDTO $order): OrderDTO
    {
        $this->debug('Save order', ['data' => $order->toArray()]);

        $response = $this->client->SaveOrder($this->context($user), $order->reverse());
        $this->debug('gRPC response', ['order_id' => $response->getId()]);

        return OrderDTO::fromData($response);
    }

    public function getExpenses(AccessTokenAwareInterface $user, ExpenseFilter $filter): ExpenseDTOList
    {
        $response = $this->client->GetExpenses($this->context($user), $filter);

        $current = $response->getMeta()?->getCurrent() ?? 1;
        $last = $response->getMeta()?->getLast() ?? 1;

        $expenses = new ExpenseDTOList($current, $last);
        // @phpstan-ignore foreach.nonIterable
        foreach ($response->getExpenses() as $item) {
            $expenses->add(ExpenseDTO::fromData($item));
        }

        $this->debug('gRPC response', ['expenses_cnt' => count($expenses)]);

        return $expenses;
    }

    /**
     * @throws \Twirp\Error
     */
    public function findExpense(AccessTokenAwareInterface $user, int $id): ExpenseDTO
    {
        $req = new IdRequest();
        $req->setId($id);

        $response = $this->client->FindExpense($this->context($user), $req);
        $this->debug('gRPC response', ['expense_id' => $response->getId()]);

        return ExpenseDTO::fromData($response);
    }

    /**
     * @throws \Twirp\Error
     */
    public function saveExpense(AccessTokenAwareInterface $user, ExpenseDTO $expense): ExpenseDTO
    {
        $this->debug('Save expense', ['data' => $expense->toArray()]);

        $response = $this->client->SaveExpense($this->context($user), $expense->reverse());
        $this->debug('gRPC response', ['expense_id' => $response->getId()]);

        return ExpenseDTO::fromData($response);
    }

    /**
     * @throws \Twirp\Error
     */
    public function getOrderTypes(AccessTokenAwareInterface $user): OrderTypeDTOList
    {
        $response = $this->client->GetOrderTypes($this->context($user), new GPBEmpty());

        $types = new OrderTypeDTOList();
        // @phpstan-ignore foreach.nonIterable
        foreach ($response->getTypes() as $item) {
            $types->add(OrderTypeDTO::fromData($item));
        }

        $this->debug('gRPC response', ['types_cnt' => count($types)]);

        return $types;
    }
}
