<?php

/**
 * User: morontt
 * Date: 09.02.2025
 * Time: 10:56
 */

namespace TeleBot\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    private AuthenticationUtils $authUtils;

    public function __construct(AuthenticationUtils $authUtils)
    {
        $this->authUtils = $authUtils;
    }

    #[Route('/login', name: 'login')]
    public function loginAction(): Response
    {
        return $this->render('auth/login.html.twig', [
            'last_username' => $this->authUtils->getLastUsername(),
            'error' => $this->authUtils->getLastAuthenticationError(),
        ]);
    }
}
