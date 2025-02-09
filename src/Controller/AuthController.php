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

class AuthController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function loginAction(): Response
    {
        return $this->render('auth/login.html.twig');
    }
}
