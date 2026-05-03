<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 25.09.2025
 * Time: 07:59
 */

namespace TeleBot\Controller\Record;

use AutoNotes\Server\OrderFilter;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use TeleBot\Controller\RecordController;
use TeleBot\DTO\CarDTO;
use TeleBot\DTO\CostDTO;
use TeleBot\DTO\OrderDTO;
use TeleBot\DTO\OrderTypeDTO;
use TeleBot\Form\Filters\OrderFilterForm;
use TeleBot\Form\OrderForm;
use TeleBot\Service\RPC\OrderRepository as RpcOrderRepository;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;

#[Route('/records/order')]
class OrderController extends RecordController
{
    public function __construct(
        private readonly RpcOrderRepository $rpcOrderRepository,
        private readonly RpcUserRepository $rpcUserRepository
    ) {
    }

    #[Route('', name: 'order_list', defaults: ['page' => 1])]
    public function listAction(Request $request): Response
    {
        $limit = (int)$request->query->get('limit', 10);
        $page = (int)$request->query->get('page', 1);

        $filterForm = $this->createForm(OrderFilterForm::class);
        $filterData = $this->handleFilterForm($filterForm, $request);

        $filterObj = new OrderFilter($filterData);
        $filterObj
            ->setPage($page)
            ->setLimit($limit)
        ;

        $user = $this->getAppUser();

        return $this->render('record/order/list.html.twig', [
            'items' => $this->rpcOrderRepository->getOrders($user, $filterObj),
            'offset' => $this->offset($page, $limit),
            'filter' => $filterForm,
        ]);
    }

    /**
     * @throws \Twirp\Error
     */
    #[Route('/add', name: 'order_add')]
    public function createAction(Request $request): Response
    {
        $orderDto = new OrderDTO();
        $orderDto
            ->setDate(new DateTime())
        ;

        $user = $this->getAppUser();
        $userSettings = $this->rpcUserRepository->getUserSettings($user);
        if ($userSettings && $userSettings->hasDefaultCurrency()) {
            $costDto = new CostDTO();
            $currency = $userSettings->getDefaultCurrency();
            if ($currency) {
                $costDto->setCurrencyCode($currency->getCode());
            }

            $orderDto->setCost($costDto);
        }

        $form = $this->createForm(OrderForm::class, $orderDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->rpcOrderRepository->saveOrder($user, $form->getData());

            return $this->redirectToRoute('order_list');
        }

        return $this->render('record/order/add.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @throws \Twirp\Error
     */
    #[Route('/{id}/edit', name: 'order_edit', requirements: ['id' => Requirement::DIGITS])]
    public function editAction(Request $request, string $id): Response
    {
        $user = $this->getAppUser();
        $orderDto = $this->rpcOrderRepository->findOrder($user, (int)$id);

        $form = $this->createForm(OrderForm::class, $orderDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->rpcOrderRepository->saveOrder($user, $form->getData());

            return $this->redirectToRoute('order_list');
        }

        return $this->render('record/order/add.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    protected function getFilterData(array $data): array
    {
        $filterArray = [];

        if (isset($data['car']) && $data['car'] instanceof CarDTO) {
            $filterArray['car_id'] = $data['car']->getId();
        }
        if (isset($data['type']) && $data['type'] instanceof OrderTypeDTO) {
            $filterArray['type_id'] = $data['type']->getId();
        }

        return $filterArray;
    }
}
