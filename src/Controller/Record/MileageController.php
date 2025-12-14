<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 14.12.2025
 */

namespace TeleBot\Controller\Record;

use AutoNotes\Server\MileageFilter;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TeleBot\Controller\BaseController;
use TeleBot\DTO\MileageDTO;
use TeleBot\Form\ExpenseForm;
use TeleBot\Service\RPC\CarRepository as RpcCarRepository;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;

#[Route('/records/mileage')]
class MileageController extends BaseController
{
    public function __construct(
        private readonly RpcCarRepository $rpcCarRepository,
        private readonly RpcUserRepository $rpcUserRepository,
    ) {
    }

    #[Route('', name: 'mileage_list', defaults: ['page' => 1])]
    public function listAction(Request $request): Response
    {
        $limit = (int)$request->query->get('limit', 10);
        $page = (int)$request->query->get('page', 1);

        $filterObj = new MileageFilter();
        $filterObj
            ->setPage($page)
            ->setLimit($limit)
        ;

        $user = $this->getAppUser();

        return $this->render('record/mileage/list.html.twig', [
            'expenses' => $this->rpcCarRepository->getMileages($user, $filterObj),
            'offset' => $this->offset($page, $limit),
        ]);
    }

    /**
     * @throws \Twirp\Error
     */
    #[Route('/add', name: 'mileage_add')]
    public function createAction(Request $request): Response
    {
        $mileageDto = new MileageDTO();
        $mileageDto
            ->setDate(new DateTime())
        ;

        $user = $this->getAppUser();
        $userSettings = $this->rpcUserRepository->getUserSettings($user);
        if ($userSettings && $userSettings->hasDefaultCar()) {
            $mileageDto->setCar($userSettings->getDefaultCar());
        }

        $form = $this->createForm(ExpenseForm::class, $mileageDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->rpcCarRepository->saveMileage($user, $form->getData());

            return $this->redirectToRoute('mileage_list');
        }

        return $this->render('record/mileage/add.html.twig', [
            'form' => $form,
        ]);
    }
}
