<?php

/**
 * User: morontt
 * Date: 31.08.2025
 * Time: 13:17
 */

namespace TeleBot\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ErrorController extends AbstractController
{
    #[Route('/_errors/{code}', methods: ['GET'])]
    public function renderAction(string $code): Response
    {
        if (!$this->container->has('twig')) {
            throw new LogicException('Twig is not available');
        }

        /** @var \Twig\Environment $twig */
        $twig = $this->container->get('twig');
        $template = sprintf('errors/error%s.html.twig', $code);
        if (!$twig->getLoader()->exists($template)) {
            $template = 'errors/error.html.twig';
        }

        return new Response($twig->render($template));
    }
}
