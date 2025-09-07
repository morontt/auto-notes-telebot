<?php

/**
 * User: morontt
 * Date: 23.03.2025
 * Time: 13:13
 */

namespace TeleBot\Service\RPC;

use AutoNotes\Server\FuelRepositoryClient;
use AutoNotes\Server\Limit;
use Google\Protobuf\GPBEmpty;
use Psr\Log\LoggerInterface;
use TeleBot\DTO\FillingStationDTO;
use TeleBot\DTO\FuelDTO;
use TeleBot\DTO\FuelTypeDTO;
use TeleBot\Security\AccessTokenAwareInterface;

class FuelRepository extends AbstractRepository
{
    private FuelRepositoryClient $client;

    public function __construct(string $grpcUrl, private readonly LoggerInterface $logger)
    {
        $this->client = new FuelRepositoryClient($grpcUrl);
    }

    /**
     * @return FuelDTO[]
     *
     * @throws \Twirp\Error
     */
    public function getFuels(AccessTokenAwareInterface $user, int $limit = 7): array
    {
        $limitObj = new Limit();
        $limitObj->setLimit($limit);

        $response = $this->client->GetFuels($this->context($user), $limitObj);
        $fuels = $response->getFuels();
        $this->logger->debug('gRPC response', ['fuels_cnt' => count($fuels)]);

        $result = [];
        foreach ($fuels as $item) {
            $result[] = FuelDTO::fromData($item);
        }

        return $result;
    }

    /**
     * @throws \Twirp\Error
     */
    public function saveFuel(AccessTokenAwareInterface $user, FuelDTO $fuel): FuelDTO
    {
        $response = $this->client->SaveFuel($this->context($user), $fuel->reverse());
        $this->logger->debug('gRPC response', ['fuel_id' => $response->getId()]);

        return FuelDTO::fromData($response);
    }

    /**
     * @return FillingStationDTO[]
     *
     * @throws \Twirp\Error
     */
    public function getFillingStations(AccessTokenAwareInterface $user): array
    {
        $response = $this->client->GetFillingStations($this->context($user), new GPBEmpty());
        $stations = $response->getStations();
        $this->logger->debug('gRPC response', ['stations_cnt' => count($stations)]);

        $result = [];
        foreach ($stations as $item) {
            $result[] = FillingStationDTO::fromData($item);
        }

        return $result;
    }

    /**
     * @return FuelTypeDTO[]
     *
     * @throws \Twirp\Error
     */
    public function getFuelTypes(AccessTokenAwareInterface $user): array
    {
        $response = $this->client->GetFuelTypes($this->context($user), new GPBEmpty());
        $types = $response->getTypes();
        $this->logger->debug('gRPC response', ['types_cnt' => count($types)]);

        $result = [];
        foreach ($types as $item) {
            $result[] = FuelTypeDTO::fromData($item);
        }

        return $result;
    }
}
