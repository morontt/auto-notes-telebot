<?php

/**
 * User: morontt
 * Date: 15.12.2024
 * Time: 23:35
 */

namespace TeleBot\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TeleBot\Security\AccessTokenAwareInterface;
use TeleBot\Service\RPC\FuelRepository;

class IndexController extends AbstractController
{
    #[Route('/')]
    public function indexAction(): Response
    {
        return $this->render('index/index.html.twig');
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboardAction(FuelRepository $rpcUserRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return new Response(Response::$statusTexts[Response::HTTP_FORBIDDEN], Response::HTTP_FORBIDDEN);
        }

        if (!$user instanceof AccessTokenAwareInterface) {
            throw new LogicException(sprintf('User "%s" not supported', get_debug_type($user)));
        }

        $fuels = $rpcUserRepository->getFuels($user);

        return $this->render('index/dashboard.html.twig', [
            'fuels' => $fuels,
        ]);
    }
}
