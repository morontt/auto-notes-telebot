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
use TeleBot\Repository\CodeRepository;

class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'settings')]
    public function settingsAction(CodeRepository $repository): Response
    {
        /* @var \TeleBot\Security\User $user */
        $user = $this->getUser();
        if (!$user) {
            return new Response(Response::$statusTexts[Response::HTTP_FORBIDDEN], Response::HTTP_FORBIDDEN);
        }

        $code = $repository->getLastByUser($user->getUserId());

        return $this->render('settings/settings.html.twig', [
            'withCode' => (bool)$code,
        ]);
    }

    #[Route('/settings/connect', name: 'tg_connect')]
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
            } else {
                $this->addFlash('error', 'Код не найден или просрочен, нужен новый');
            }

            return $this->redirectToRoute('tg_connect');
        }

        return $this->render('settings/connect.html.twig', [
            'form' => $form,
        ]);
    }
}
