<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 25.09.2025
 * Time: 07:59
 */

namespace TeleBot\Controller\Record;

use AutoNotes\Server\OrderFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TeleBot\Controller\BaseController;
use TeleBot\Service\RPC\OrderRepository as RpcOrderRepository;

//use TeleBot\Service\RPC\UserRepository as RpcUserRepository;

#[Route('/order')]
class OrderController extends BaseController
{
    public function __construct(
        private readonly RpcOrderRepository $rpcOrderRepository,
        //private readonly RpcUserRepository $rpcUserRepository
    ) {
    }

    #[Route('', name: 'order_list', defaults: ['page' => 1])]
    public function listAction(Request $request): Response
    {
        $limit = (int)$request->query->get('limit', 10);
        $page = (int)$request->query->get('page', 1);

        $filterObj = new OrderFilter();
        $filterObj
            ->setPage($page)
            ->setLimit($limit)
        ;

        $user = $this->getAppUser();
        $orders = $this->rpcOrderRepository->getOrders($user, $filterObj);

        return $this->render('record/order/list.html.twig', [
            'orders' => $orders,
            'offset' => $page > 1 ? $limit * ($page - 1) : 0,
        ]);
    }
}
