<?php

/**
 * User: morontt
 * Date: 23.03.2025
 * Time: 13:24
 */

namespace TeleBot\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TeleBot\DTO\FuelDTO;
use TeleBot\Form\FuelForm;
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
        /* @var \TeleBot\Security\User $user */
        $user = $this->getUser();
        if (!$user) {
            return new Response(Response::$statusTexts[Response::HTTP_FORBIDDEN], Response::HTTP_FORBIDDEN);
        }

        $userSettings = $this->rpcUserRepository->getUserSettings($user);

        $fuelDto = new FuelDTO();

        $form = $this->createForm(FuelForm::class, $fuelDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('fuel/add.html.twig', [
            'form' => $form,
        ]);
    }
}
