<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 28.08.2025
 * Time: 08:53
 */

namespace TeleBot\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Twirp\Error;
use Twirp\ErrorCode;

class TwirpErrorListener
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();
        if ($e instanceof Error) {
            $statusCode = ErrorCode::serverHTTPStatusFromErrorCode($e->getErrorCode()) ?: 500;

            if ($statusCode >= 500) {
                $this->logger->critical('gRPC internal error', [
                    'error' => $e,
                ]);
            } else {
                $this->logger->error('gRPC error', [
                    'error' => $e,
                ]);
            }

            $errorBody = '<!DOCTYPE html>';
            $errorBody .= '<html lang="en">';
            $errorBody .= '<head><title>Pu pu puuu...</title></head>';
            $errorBody .= '<body>gRPC error</body>';
            $errorBody .= '</html>';

            $response = new Response($errorBody);
            $response->setStatusCode($statusCode);
            $response->headers->set('Content-Type', 'text/html');

            $event->setResponse($response);
        }
    }
}
