<?php

/**
 * User: morontt
 * Date: 15.12.2024
 * Time: 23:35
 */

namespace TeleBot\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/')]
    public function indexAction(): Response
    {
        return $this->render('index/index.html.twig');
    }

    #[Route('/dashboard')]
    public function dashboardAction(): Response
    {
        return $this->render('index/dashboard.html.twig');
    }
}
