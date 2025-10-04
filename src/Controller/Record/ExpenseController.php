<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 03.10.2025
 * Time: 22:07
 */

namespace TeleBot\Controller\Record;

use AutoNotes\Server\ExpenseFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TeleBot\Controller\BaseController;
use TeleBot\Service\RPC\OrderRepository as RpcOrderRepository;

#[Route('/records/expense')]
class ExpenseController extends BaseController
{
    public function __construct(
        private readonly RpcOrderRepository $rpcOrderRepository,
    ) {
    }

    #[Route('', name: 'expense_list', defaults: ['page' => 1])]
    public function listAction(Request $request): Response
    {
        $limit = (int)$request->query->get('limit', 10);
        $page = (int)$request->query->get('page', 1);

        $filterObj = new ExpenseFilter();
        $filterObj
            ->setPage($page)
            ->setLimit($limit)
        ;

        $user = $this->getAppUser();

        return $this->render('record/expense/list.html.twig', [
            'expenses' => $this->rpcOrderRepository->getExpenses($user, $filterObj),
            'offset' => $this->offset($page, $limit),
        ]);
    }
}
