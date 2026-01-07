<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 05.01.2026
 */

namespace TeleBot\Controller\Record;

use AutoNotes\Server\ServiceFilter;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use TeleBot\Controller\BaseController;
use TeleBot\DTO\CostDTO;
use TeleBot\DTO\OrderDTO;
use TeleBot\Form\OrderForm;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;
use TeleBot\Service\RPC\CarRepository as RpcCarRepository;

#[Route('/records/service')]
class ServiceController extends BaseController
{
    public function __construct(
        private readonly RpcCarRepository $rpcCarRepository,
        private readonly RpcUserRepository $rpcUserRepository
    ) {
    }

    #[Route('', name: 'service_list', defaults: ['page' => 1])]
    public function listAction(Request $request): Response
    {
        $limit = (int)$request->query->get('limit', 10);
        $page = (int)$request->query->get('page', 1);

        $filterObj = new ServiceFilter();
        $filterObj
            ->setPage($page)
            ->setLimit($limit)
        ;

        $user = $this->getAppUser();

        return $this->render('record/service/list.html.twig', [
            'items' => $this->rpcCarRepository->getServices($user, $filterObj),
            'offset' => $this->offset($page, $limit),
        ]);
    }

    /**
     * @throws \Twirp\Error
     */
    #[Route('/add', name: 'service_add')]
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
            $this->rpcCarRepository->saveService($user, $form->getData());

            return $this->redirectToRoute('service_list');
        }

        return $this->render('record/service/add.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @throws \Twirp\Error
     */
    #[Route('/{id}/edit', name: 'service_edit', requirements: ['id' => Requirement::DIGITS])]
    public function editAction(Request $request, string $id): Response
    {
        $user = $this->getAppUser();
        $orderDto = $this->rpcCarRepository->findService($user, (int)$id);

        $form = $this->createForm(OrderForm::class, $orderDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->rpcCarRepository->saveService($user, $form->getData());

            return $this->redirectToRoute('service_list');
        }

        return $this->render('record/service/add.html.twig', [
            'form' => $form,
        ]);
    }
}
