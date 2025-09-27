<?php
/**
 * User: morontt
 * Date: 23.03.2025
 * Time: 13:13
 */

declare(strict_types=1);

namespace TeleBot\Service\RPC;

use AutoNotes\Server\FuelFilter;
use AutoNotes\Server\FuelRepositoryClient;
use AutoNotes\Server\IdRequest;
use Google\Protobuf\GPBEmpty;
use Psr\Log\LoggerInterface;
use TeleBot\DTO\FillingStationDTO;
use TeleBot\DTO\FuelDTO;
use TeleBot\DTO\FuelTypeDTO;
use TeleBot\DTO\List\FillingStationDTOList;
use TeleBot\DTO\List\FuelDTOList;
use TeleBot\DTO\List\FuelTypeDTOList;
use TeleBot\Security\AccessTokenAwareInterface;

class FuelRepository extends AbstractRepository
{
    private FuelRepositoryClient $client;

    public function __construct(string $grpcUrl, private readonly LoggerInterface $logger)
    {
        $this->client = new FuelRepositoryClient($grpcUrl);
    }

    /**
     * @throws \Twirp\Error
     */
    public function getFuels(AccessTokenAwareInterface $user, FuelFilter $filter): FuelDTOList
    {
        $response = $this->client->GetFuels($this->context($user), $filter);

        $current = $response->getMeta()?->getCurrent() ?? 1;
        $last = $response->getMeta()?->getLast() ?? 1;

        $fuels = new FuelDTOList($current, $last);
        // @phpstan-ignore foreach.nonIterable
        foreach ($response->getFuels() as $item) {
            $fuels->add(FuelDTO::fromData($item));
        }

        $this->logger->debug('gRPC response', ['fuels_cnt' => count($fuels)]);

        return $fuels;
    }

    /**
     * @throws \Twirp\Error
     */
    public function findFuel(AccessTokenAwareInterface $user, int $id): FuelDTO
    {
        $req = new IdRequest();
        $req->setId($id);

        $response = $this->client->FindFuel($this->context($user), $req);
        $this->logger->debug('gRPC response', ['fuel_id' => $response->getId()]);

        return FuelDTO::fromData($response);
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
     * @throws \Twirp\Error
     */
    public function getFillingStations(AccessTokenAwareInterface $user): FillingStationDTOList
    {
        $response = $this->client->GetFillingStations($this->context($user), new GPBEmpty());

        $stations = new FillingStationDTOList();
        // @phpstan-ignore foreach.nonIterable
        foreach ($response->getStations() as $item) {
            $stations->add(FillingStationDTO::fromData($item));
        }

        $this->logger->debug('gRPC response', ['stations_cnt' => count($stations)]);

        return $stations;
    }

    /**
     * @throws \Twirp\Error
     */
    public function getFuelTypes(AccessTokenAwareInterface $user): FuelTypeDTOList
    {
        $response = $this->client->GetFuelTypes($this->context($user), new GPBEmpty());

        $types = new FuelTypeDTOList();
        // @phpstan-ignore foreach.nonIterable
        foreach ($response->getTypes() as $item) {
            $types->add(FuelTypeDTO::fromData($item));
        }

        $this->logger->debug('gRPC response', ['types_cnt' => count($types)]);

        return $types;
    }
}
