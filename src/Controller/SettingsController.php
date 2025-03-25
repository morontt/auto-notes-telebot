<?php

/**
 * User: morontt
 * Date: 23.02.2025
 * Time: 20:53
 */

namespace TeleBot\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TeleBot\Form\Settings\CodeForm;
use TeleBot\Form\Settings\UserSettingsForm;
use TeleBot\Repository\CodeRepository;
use TeleBot\Service\RPC\UserRepository as RpcUserRepository;

#[Route('/settings')]
class SettingsController extends AbstractController
{
    #[Route('/', name: 'settings')]
    public function settingsAction(CodeRepository $repository, RpcUserRepository $rpcUserRepository): Response
    {
        /* @var \TeleBot\Security\User $user */
        $user = $this->getUser();
        if (!$user) {
            return new Response(Response::$statusTexts[Response::HTTP_FORBIDDEN], Response::HTTP_FORBIDDEN);
        }

        $code = $repository->getLastByUser($user->getUserId());

        return $this->render('settings/settings.html.twig', [
            'withCode' => (bool)$code,
            'cars' => $rpcUserRepository->getCars($user),
            'currency' => $rpcUserRepository->getDefaultCurrency($user),
        ]);
    }

    #[Route('/connect', name: 'tg_connect')]
    public function connectAction(Request $request, CodeRepository $repository): Response
    {
        /* @var \TeleBot\Security\User $user */
        $user = $this->getUser();
        if (!$user) {
            return new Response(Response::$statusTexts[Response::HTTP_FORBIDDEN], Response::HTTP_FORBIDDEN);
        }

        $form = $this->createForm(CodeForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $code = $form->get('code')->getData();

            $codeObj = $repository->getUnusedCode($code);
            if ($codeObj) {
                $codeObj
                    ->setUserId($user->getUserId())
                    ->setUpdatedAt(new DateTime())
                ;
                $repository->save($codeObj);

                $this->addFlash('success', 'Аккаунт привязан :)');

                return $this->redirectToRoute('settings');
            }

            $this->addFlash('error', 'Код не найден или просрочен, нужен новый');

            return $this->redirectToRoute('tg_connect');
        }

        return $this->render('settings/connect.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit', name: 'settings_edit')]
    public function settingsEditAction(Request $request, RpcUserRepository $rpcUserRepository): Response
    {
        /* @var \TeleBot\Security\User $user */
        $user = $this->getUser();
        if (!$user) {
            return new Response(Response::$statusTexts[Response::HTTP_FORBIDDEN], Response::HTTP_FORBIDDEN);
        }

        $userSettings = $rpcUserRepository->getUserSettings($user);

        $form = $this->createForm(UserSettingsForm::class, $userSettings);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // TODO handle errors
            $rpcUserRepository->saveUserSettings($user, $form->getData());

            return $this->redirectToRoute('settings');
        }

        return $this->render('settings/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
