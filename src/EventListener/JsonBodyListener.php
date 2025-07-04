<?php

/**
 * User: morontt
 * Date: 27.02.2025
 * Time: 09:43
 */

namespace TeleBot\EventListener;

use JsonException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class JsonBodyListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param RequestEvent $event
     *
     * @throws BadRequestHttpException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $method = $request->getMethod();

        if (in_array($method, ['POST', 'PUT', 'DELETE'])
            && !count($request->request->all())
        ) {
            $contentType = $request->headers->get('Content-Type');

            $format = null === $contentType
                ? $request->getRequestFormat()
                : $request->getFormat($contentType);

            if ($format === 'json') {
                $content = $request->getContent();
                if (!empty($content)) {
                    try {
                        $data = json_decode(
                            $content,
                            true,
                            512,
                            JSON_BIGINT_AS_STRING | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR
                        );

                        if (is_array($data)) {
                            $request->request = new InputBag($data);

                            return;
                        }
                    } catch (JsonException $e) {
                        $this->logger->error('JsonException', ['exception' => $e]);
                    }

                    throw new BadRequestHttpException('Invalid ' . $format . ' message received');
                }
            }
        }
    }
}
