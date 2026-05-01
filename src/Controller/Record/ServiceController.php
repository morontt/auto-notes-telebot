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
use TeleBot\Controller\RecordController;
use TeleBot\DTO\CarDTO;
use TeleBot\DTO\CostDTO;
use TeleBot\DTO\ServiceDTO;
use TeleBot\Form\Filters\ServiceFilterForm;
use TeleBot\Form\ServiceForm;
use TeleBot\Service\RPC\CarRepository as RpcCarRepository;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;

#[Route('/records/service')]
class ServiceController extends RecordController
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

        $filterForm = $this->createForm(ServiceFilterForm::class);
        $filterData = $this->handleFilterForm($filterForm, $request);

        $filterObj = new ServiceFilter($filterData);
        $filterObj
            ->setPage($page)
            ->setLimit($limit)
        ;

        $user = $this->getAppUser();

        return $this->render('record/service/list.html.twig', [
            'items' => $this->rpcCarRepository->getServices($user, $filterObj),
            'offset' => $this->offset($page, $limit),
            'filter' => $filterForm,
        ]);
    }

    /**
     * @throws \Twirp\Error
     */
    #[Route('/add', name: 'service_add')]
    public function createAction(Request $request): Response
    {
        $serviceDTO = new ServiceDTO();
        $serviceDTO
            ->setDate(new DateTime())
        ;

        $user = $this->getAppUser();
        $userSettings = $this->rpcUserRepository->getUserSettings($user);
        if ($userSettings) {
            if ($userSettings->hasDefaultCurrency()) {
                $costDto = new CostDTO();
                $currency = $userSettings->getDefaultCurrency();
                if ($currency) {
                    $costDto->setCurrencyCode($currency->getCode());
                }

                $serviceDTO->setCost($costDto);
            }

            if ($userSettings->hasDefaultCar()) {
                $serviceDTO->setCar($userSettings->getDefaultCar());
            }
        }

        $form = $this->createForm(ServiceForm::class, $serviceDTO);
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
        $serviceDTO = $this->rpcCarRepository->findService($user, (int)$id);

        if (!$serviceDTO->hasCost()) {
            $userSettings = $this->rpcUserRepository->getUserSettings($user);
            if ($userSettings && $userSettings->hasDefaultCurrency()) {
                $costDto = new CostDTO();
                $currency = $userSettings->getDefaultCurrency();
                if ($currency) {
                    $costDto->setCurrencyCode($currency->getCode());
                }

                $serviceDTO->setCost($costDto);
            }
        }

        $form = $this->createForm(ServiceForm::class, $serviceDTO);
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

        return $filterArray;
    }
}
