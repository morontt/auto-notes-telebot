<?php

/**
 * User: morontt
 * Date: 23.02.2025
 * Time: 20:53
 */

namespace TeleBot\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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

        return $this->render('index/settings.html.twig', [
            'withCode' => (bool)$code,
        ]);
    }
}
