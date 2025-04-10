<?php

/**
 * User: morontt
 * Date: 23.03.2025
 * Time: 13:13
 */

namespace TeleBot\Service\RPC;

use Google\Protobuf\GPBEmpty;
use Psr\Log\LoggerInterface;
use TeleBot\DTO\FillingStationDTO;
use TeleBot\DTO\FuelDTO;
use TeleBot\Security\User;
use Twirp\Error;
use Xelbot\Com\Autonotes\FuelRepositoryClient;
use Xelbot\Com\Autonotes\Limit;

class FuelRepository extends AbstractRepository
{
    private FuelRepositoryClient $client;

    public function __construct(string $grpcUrl, private readonly LoggerInterface $logger)
    {
        $this->client = new FuelRepositoryClient($grpcUrl);
    }

    /**
     * @return FuelDTO[]
     */
    public function getFuels(User $user, int $limit = 7): array
    {
        $limitObj = new Limit();
        $limitObj->setLimit($limit);

        $result = [];
        try {
            $response = $this->client->GetFuels($this->context($user), $limitObj);
            $fuels = $response->getFuels();
            $this->logger->debug('gRPC response', ['fuels_cnt' => count($fuels)]);

            foreach ($fuels as $item) {
                $result[] = FuelDTO::fromData($item);
            }
        } catch (Error $e) {
            $this->logger->error('gRPC error', [
                'error' => $e,
            ]);
        }

        return $result;
    }

    public function saveFuel(User $user, FuelDTO $fuel): ?FuelDTO
    {
        $result = null;
        try {
            $response = $this->client->SaveFuel($this->context($user), $fuel->reverse());
            $this->logger->debug('gRPC response', ['fuel_id' => $response->getId()]);

            $result = FuelDTO::fromData($response);
        } catch (Error $e) {
            $this->logger->error('gRPC error', [
                'error' => $e,
            ]);
        }

        return $result;
    }

    /**
     * @return FillingStationDTO[]
     */
    public function getFillingStations(User $user): array
    {
        $result = [];
        try {
            $response = $this->client->GetFillingStations($this->context($user), new GPBEmpty());
            $stations = $response->getStations();
            $this->logger->debug('gRPC response', ['stations_cnt' => count($stations)]);

            foreach ($stations as $item) {
                $result[] = FillingStationDTO::fromData($item);
            }
        } catch (Error $e) {
            $this->logger->error('gRPC error', [
                'error' => $e,
            ]);
        }

        return $result;
    }
}
