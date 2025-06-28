<?php

/**
 * User: morontt
 * Date: 23.03.2025
 * Time: 13:24
 */

namespace TeleBot\Controller;

use DateTime;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TeleBot\DTO\CostDTO;
use TeleBot\DTO\FuelDTO;
use TeleBot\Form\FuelForm;
use TeleBot\Security\AccessTokenAwareInterface;
use TeleBot\Service\RPC\FuelRepository as RpcFuelRepository;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;

#[Route('/fuel')]
class FuelController extends AbstractController
{
    public function __construct(
        private readonly RpcFuelRepository $rpcFuelRepository,
        private readonly RpcUserRepository $rpcUserRepository
    ) {
    }

    #[Route('/add', name: 'fuel_add')]
    public function createAction(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return new Response(Response::$statusTexts[Response::HTTP_FORBIDDEN], Response::HTTP_FORBIDDEN);
        }

        if (!$user instanceof AccessTokenAwareInterface) {
            throw new LogicException(sprintf('User "%s" not supported', get_debug_type($user)));
        }

        $fuelDto = new FuelDTO();
        $fuelDto->setDate(new DateTime());

        $userSettings = $this->rpcUserRepository->getUserSettings($user);
        if ($userSettings) {
            if ($userSettings->hasDefaultCar()) {
                $fuelDto->setCar($userSettings->getDefaultCar());
            }
            if ($userSettings->hasDefaultCurrency()) {
                $costDto = new CostDTO();
                $costDto->setCurrencyCode($userSettings->getDefaultCurrency()->getCode());

                $fuelDto->setCost($costDto);
            }
        }

        $form = $this->createForm(FuelForm::class, $fuelDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->rpcFuelRepository->saveFuel($user, $form->getData());

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('fuel/add.html.twig', [
            'form' => $form,
        ]);
    }
}
