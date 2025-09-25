<?php
/**
 * User: morontt
 * Date: 25.09.2025
 * Time: 07:59
 */

declare(strict_types=1);

namespace TeleBot\Controller\Record;

use Symfony\Component\Routing\Attribute\Route;
use TeleBot\Controller\BaseController;
use TeleBot\Service\RPC\OrderRepository as RpcOrderRepository;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;

#[Route('/order')]
class OrderController extends BaseController
{
    public function __construct(
        private readonly RpcOrderRepository $rpcOrderRepository,
        private readonly RpcUserRepository $rpcUserRepository
    ) {
    }
}
