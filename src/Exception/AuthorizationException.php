<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 13.09.2025
 * Time: 13:30
 */

namespace TeleBot\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class AuthorizationException extends RuntimeException implements HttpExceptionInterface
{
    public function getStatusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return [];
    }
}
