<?php
/**
 * User: morontt
 * Date: 15.02.2025
 * Time: 14:31
 */

declare(strict_types=1);

namespace TeleBot\Service\RPC;

use AutoNotes\Auth\AuthClient;
use AutoNotes\Auth\LoginRequest;
use Psr\Log\LoggerInterface;
use TeleBot\LogTrait;
use Twirp\Error;

class Auth
{
    use LogTrait;

    private AuthClient $client;

    public function __construct(string $grpcUrl, LoggerInterface $logger)
    {
        $this->client = new AuthClient($grpcUrl);

        $this->setLogger($logger);
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
            $this->debug('gRPC response', ['token' => substr($response->getToken(), 0, 64) . '...']);

            return $response->getToken();
        } catch (Error $e) {
            $this->error('gRPC error', [
                'username' => $username,
                'error' => $e,
            ]);
        }

        return null;
    }
}
