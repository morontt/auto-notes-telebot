<?php

/**
 * User: morontt
 * Date: 23.03.2025
 * Time: 13:24
 */

namespace TeleBot\Controller;

use AutoNotes\Server\FuelFilter;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TeleBot\DTO\CostDTO;
use TeleBot\DTO\FuelDTO;
use TeleBot\Form\FuelForm;
use TeleBot\Service\RPC\FuelRepository as RpcFuelRepository;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;

#[Route('/fuel')]
class FuelController extends BaseController
{
    public function __construct(
        private readonly RpcFuelRepository $rpcFuelRepository,
        private readonly RpcUserRepository $rpcUserRepository
    ) {
    }

    #[Route('', name: 'fuel_list')]
    public function listAction(Request $request): Response
    {
        $limit = (int)$request->query->get('limit', 10);
        $page = (int)$request->query->get('page', 1);

        $filterObj = new FuelFilter();
        $filterObj
            ->setPage($page)
            ->setLimit($limit)
        ;

        $user = $this->getAppUser();
        $fuels = $this->rpcFuelRepository->getFuels($user, $filterObj);

        return $this->render('fuel/list.html.twig', [
            'fuels' => $fuels,
            'offset' => $page > 1 ? $limit * ($page - 1) : 0,
        ]);
    }

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
            $this->rpcFuelRepository->saveFuel($user, $form->getData());

            return $this->redirectToRoute('fuel_list');
        }

        return $this->render('fuel/add.html.twig', [
            'form' => $form,
        ]);
    }
}
