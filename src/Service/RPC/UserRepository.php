<?php

/**
 * User: morontt
 * Date: 02.03.2025
 * Time: 19:25
 */

namespace TeleBot\Service\RPC;

use Google\Protobuf\GPBEmpty;
use Psr\Log\LoggerInterface;
use TeleBot\DTO\CarDTO;
use TeleBot\DTO\FuelDTO;
use TeleBot\Security\User;
use Twirp\Context;
use Twirp\Error;
use Xelbot\Com\Autonotes\Limit;
use Xelbot\Com\Autonotes\UserRepositoryClient;

class UserRepository
{
    private UserRepositoryClient $client;

    public function __construct(string $grpcUrl, private readonly LoggerInterface $logger)
    {
        $this->client = new UserRepositoryClient($grpcUrl);
    }

    /**
     * @param User $user
     *
     * @return CarDTO[]
     */
    public function GetCars(User $user): array
    {
        $result = [];
        try {
            $response = $this->client->GetCars($this->context($user), new GPBEmpty());
            $cars = $response->getCars();
            $this->logger->debug('gRPC response', ['cars_cnt' => count($cars)]);

            foreach ($cars as $item) {
                $result[] = new CarDTO($item);
            }
        } catch (Error $e) {
            $this->logger->error('gRPC error', [
                'error' => $e,
            ]);
        }

        return $result;
    }

    public function GetFuels(User $user, int $limit = 7): array
    {
        $limitObj = new Limit();
        $limitObj->setLimit($limit);

        $result = [];
        try {
            $response = $this->client->GetFuels($this->context($user), $limitObj);
            $fuels = $response->getFuels();
            $this->logger->debug('gRPC response', ['fuels_cnt' => count($fuels)]);

            foreach ($fuels as $item) {
                $result[] = new FuelDTO($item);
            }
        } catch (Error $e) {
            $this->logger->error('gRPC error', [
                'error' => $e,
            ]);
        }

        return $result;
    }

    private function context(User $user): array
    {
        return Context::withHttpRequestHeaders([], [
            'Authorization' => 'Bearer ' . $user->getAccessToken(),
        ]);
    }
}
