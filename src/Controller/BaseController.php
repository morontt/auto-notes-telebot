<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 13.09.2025
 * Time: 13:22
 */

namespace TeleBot\Controller;

use AutoNotes\Server\ErrorCode;
use AutoNotes\Server\TwirpError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use TeleBot\Exception\AuthorizationException;
use TeleBot\Exception\InvalidUserException;
use TeleBot\Security\AccessTokenAwareInterface;
use UnexpectedValueException;

class BaseController extends AbstractController
{
    protected function getAppUser(): AccessTokenAwareInterface
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AuthorizationException();
        }

        if (!$user instanceof AccessTokenAwareInterface) {
            throw new InvalidUserException(sprintf('User "%s" not supported', get_debug_type($user)));
        }

        return $user;
    }

    protected function offset(int $page, int $limit): int
    {
        return $page > 1 ? $limit * ($page - 1) : 0;
    }

    protected function twirpErrorToForm(TwirpError $error, FormInterface $form): bool
    {
        $catched = false;
        $errorCode = $this->exctractErrorCode($error);
        switch ($errorCode) {
            case ErrorCode::E002:
                $catched = true;
                if ($form->has('distance')) {
                    $form
                        ->get('distance')
                        ->addError(new FormError('Неправильный пробег'))
                    ;
                }
                break;
        }

        return $catched;
    }

    private function exctractErrorCode(TwirpError $error): ?int
    {
        $strings = explode(':', $error->getMessage());
        if (count($strings) > 1) {
            try {
                $code = ErrorCode::value($strings[0]);

                return $code;
            } catch (UnexpectedValueException $e) {
            }
        }

        return null;
    }
}
