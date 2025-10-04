<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 22.02.2025
 * Time: 10:17
 */

namespace TeleBot\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TeleBot\Form\UpdatesForm;
use Xelbot\Telegram\Robot;

class DebugController extends AbstractController
{
    public function updatesFormAction(Request $request, Robot $bot): Response
    {
        $form = $this->createForm(UpdatesForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $updates = json_decode(
                $data['data'],
                true,
                512,
                JSON_BIGINT_AS_STRING | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR
            );
            if (!is_array($updates)) {
                return new Response(Response::$statusTexts[Response::HTTP_BAD_REQUEST], Response::HTTP_BAD_REQUEST);
            }

            $bot->handle($updates);
        }

        return $this->render('debug/updatesForm.html.twig', [
            'form' => $form,
        ]);
    }
}
