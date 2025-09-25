<?php
/**
 * User: morontt
 * Date: 15.12.2024
 * Time: 23:35
 */

declare(strict_types=1);

namespace TeleBot\Controller;

use AutoNotes\Server\FuelFilter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TeleBot\Service\RPC\FuelRepository;

class IndexController extends BaseController
{
    #[Route('/')]
    public function indexAction(): Response
    {
        return $this->render('index/index.html.twig');
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboardAction(FuelRepository $rpcFuelRepository): Response
    {
        $filterObj = new FuelFilter();
        $filterObj->setLimit(7);

        $user = $this->getAppUser();
        $fuels = $rpcFuelRepository->getFuels($user, $filterObj);

        return $this->render('index/dashboard.html.twig', [
            'fuels' => $fuels,
        ]);
    }
}
