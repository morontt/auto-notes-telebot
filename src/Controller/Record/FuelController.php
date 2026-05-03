<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 23.03.2025
 * Time: 13:24
 */

namespace TeleBot\Controller\Record;

use AutoNotes\Server\FuelFilter;
use AutoNotes\Server\TwirpError;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use TeleBot\Controller\RecordController;
use TeleBot\DTO\CarDTO;
use TeleBot\DTO\CostDTO;
use TeleBot\DTO\FillingStationDTO;
use TeleBot\DTO\FuelDTO;
use TeleBot\DTO\FuelTypeDTO;
use TeleBot\Form\Filters\FuelFilterForm;
use TeleBot\Form\FuelForm;
use TeleBot\Service\RPC\FuelRepository as RpcFuelRepository;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;

#[Route('/records/fuel')]
class FuelController extends RecordController
{
    public function __construct(
        private readonly RpcFuelRepository $rpcFuelRepository,
        private readonly RpcUserRepository $rpcUserRepository,
    ) {
    }

    #[Route('', name: 'fuel_list', defaults: ['page' => 1])]
    public function listAction(Request $request): Response
    {
        $limit = (int)$request->query->get('limit', 10);
        $page = (int)$request->query->get('page', 1);

        $filterForm = $this->createForm(FuelFilterForm::class);
        $filterData = $this->handleFilterForm($filterForm, $request);

        $filterObj = new FuelFilter($filterData);
        $filterObj
            ->setPage($page)
            ->setLimit($limit)
        ;

        $user = $this->getAppUser();

        return $this->render('record/fuel/list.html.twig', [
            'items' => $this->rpcFuelRepository->getFuels($user, $filterObj),
            'offset' => $this->offset($page, $limit),
            'filter' => $filterForm,
        ]);
    }

    /**
     * @throws \Twirp\Error
     */
    #[Route('/add', name: 'fuel_add')]
    public function createAction(Request $request): Response
    {
        $fuelDto = new FuelDTO();
        $fuelDto->setDate(new DateTime());

        $user = $this->getAppUser();
        $userSettings = $this->rpcUserRepository->getUserSettings($user);
        if ($userSettings) {
            if ($userSettings->hasDefaultCar()) {
                $fuelDto->setCar($userSettings->getDefaultCar());
            }
            if ($userSettings->hasDefaultCurrency()) {
                $costDto = new CostDTO();
                $currency = $userSettings->getDefaultCurrency();
                if ($currency) {
                    $costDto->setCurrencyCode($currency->getCode());
                }

                $fuelDto->setCost($costDto);
            }
            if ($userSettings->hasDefaultFuelType()) {
                $fuelDto->setType($userSettings->getDefaultFuelType());
            }
        }

        $form = $this->createForm(FuelForm::class, $fuelDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->rpcFuelRepository->saveFuel($user, $form->getData());
            } catch (TwirpError $e) {
                $catched = $this->twirpErrorToForm($e, $form);
                if ($catched) {
                    return $this->render('record/fuel/add.html.twig', [
                        'form' => $form,
                    ]);
                }

                throw $e;
            }

            return $this->redirectToRoute('fuel_list');
        }

        return $this->render('record/fuel/add.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @throws \Twirp\Error
     */
    #[Route('/{id}/edit', name: 'fuel_edit', requirements: ['id' => Requirement::DIGITS])]
    public function editAction(Request $request, string $id): Response
    {
        $user = $this->getAppUser();
        $fuelDto = $this->rpcFuelRepository->findFuel($user, (int)$id);

        $form = $this->createForm(FuelForm::class, $fuelDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->rpcFuelRepository->saveFuel($user, $form->getData());
            } catch (TwirpError $e) {
                $catched = $this->twirpErrorToForm($e, $form);
                if ($catched) {
                    return $this->render('record/fuel/add.html.twig', [
                        'form' => $form,
                    ]);
                }

                throw $e;
            }

            return $this->redirectToRoute('fuel_list');
        }

        return $this->render('record/fuel/add.html.twig', [
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
        if (isset($data['station']) && $data['station'] instanceof FillingStationDTO) {
            $filterArray['station_id'] = $data['station']->getId();
        }
        if (isset($data['type']) && $data['type'] instanceof FuelTypeDTO) {
            $filterArray['type_id'] = $data['type']->getId();
        }

        return $filterArray;
    }
}
