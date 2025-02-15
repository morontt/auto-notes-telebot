<?php

/**
 * User: morontt
 * Date: 15.02.2025
 * Time: 14:31
 */

namespace TeleBot\Service;

use Psr\Log\LoggerInterface;
use Twirp\Error;
use Xelbot\Com\Autonotes\AuthClient;
use Xelbot\Com\Autonotes\LoginRequest;

class Auth
{
    private LoggerInterface $logger;
    private AuthClient $client;

    public function __construct(string $grpcUrl, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->client = new AuthClient($grpcUrl);
    }

    public function getToken(string $username, string $password): ?string
    {
        $loginRequest = new LoginRequest();
        $loginRequest
            ->setUsername($username)
            ->setPassword($password)
        ;

        try {
            $response = $this->client->GetToken([], $loginRequest);
            $this->logger->debug('gRPC response', ['token' => substr($response->getToken(), 0, 64) . '...']);

            return $response->getToken();
        } catch (Error $e) {
            $this->logger->error('gRPC error', [
                'username' => $username,
                'error' => $e,
            ]);
        }

        return null;
    }
}
