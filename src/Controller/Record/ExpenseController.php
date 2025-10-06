<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 03.10.2025
 * Time: 22:07
 */

namespace TeleBot\Controller\Record;

use AutoNotes\Server\ExpenseFilter;
use AutoNotes\Server\ExpenseType;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use TeleBot\Controller\BaseController;
use TeleBot\DTO\CostDTO;
use TeleBot\DTO\ExpenseDTO;
use TeleBot\Form\ExpenseForm;
use TeleBot\Service\RPC\OrderRepository as RpcOrderRepository;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;

#[Route('/records/expense')]
class ExpenseController extends BaseController
{
    public function __construct(
        private readonly RpcOrderRepository $rpcOrderRepository,
        private readonly RpcUserRepository $rpcUserRepository,
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

    #[Route('/add', name: 'expense_add')]
    public function createAction(Request $request): Response
    {
        $expenseDto = new ExpenseDTO();
        $expenseDto
            ->setDate(new DateTime())
            ->setType(ExpenseType::OTHER)
        ;

        $user = $this->getAppUser();
        $userSettings = $this->rpcUserRepository->getUserSettings($user);
        if ($userSettings && $userSettings->hasDefaultCurrency()) {
            $costDto = new CostDTO();
            $currency = $userSettings->getDefaultCurrency();
            if ($currency) {
                $costDto->setCurrencyCode($currency->getCode());
            }

            $expenseDto->setCost($costDto);
        }

        $form = $this->createForm(ExpenseForm::class, $expenseDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->rpcOrderRepository->saveExpense($user, $form->getData());

            return $this->redirectToRoute('expense_list');
        }

        return $this->render('record/expense/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'expense_edit', requirements: ['id' => Requirement::DIGITS])]
    public function editAction(Request $request, string $id): Response
    {
        $user = $this->getAppUser();
        $expenseDto = $this->rpcOrderRepository->findExpense($user, (int)$id);

        $form = $this->createForm(ExpenseForm::class, $expenseDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->rpcOrderRepository->saveExpense($user, $form->getData());

            return $this->redirectToRoute('expense_list');
        }

        return $this->render('record/expense/add.html.twig', [
            'form' => $form,
        ]);
    }
}
