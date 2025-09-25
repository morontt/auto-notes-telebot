<?php
/**
 * User: morontt
 * Date: 27.02.2025
 * Time: 09:23
 */

declare(strict_types=1);

namespace TeleBot\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Xelbot\Telegram\Robot;

class WebHookController extends AbstractController
{
    /**
     * @var Robot
     */
    private Robot $telegramBot;

    private string $secretToken;

    /**
     * @param Robot $telegramBot
     * @param string $secretToken
     */
    public function __construct(Robot $telegramBot, string $secretToken)
    {
        $this->telegramBot = $telegramBot;
        $this->secretToken = $secretToken;
    }

    #[Route('/webhook/{token}', methods: ['POST'])]
    public function indexAction(Request $request, string $token): Response
    {
        if (!hash_equals(sha1($this->secretToken), sha1($token))) {
            return new Response("Get out!\n", 403, ['content-type' => 'text/plain']);
        }

        $this->telegramBot->handle($request->request->all());

        return new Response("ok\n", 200, ['content-type' => 'text/plain']);
    }
}
