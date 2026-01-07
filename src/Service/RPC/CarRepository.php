<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 14.12.2025
 */

namespace TeleBot\Service\RPC;

use AutoNotes\Server\CarRepositoryClient;
use AutoNotes\Server\IdRequest;
use AutoNotes\Server\MileageFilter;
use AutoNotes\Server\ServiceFilter;
use Psr\Log\LoggerInterface;
use TeleBot\DTO\List\MileageDTOList;
use TeleBot\DTO\List\ServiceDTOList;
use TeleBot\DTO\MileageDTO;
use TeleBot\DTO\ServiceDTO;
use TeleBot\LogTrait;
use TeleBot\Security\AccessTokenAwareInterface;

class CarRepository extends AbstractRepository
{
    use LogTrait;

    private CarRepositoryClient $client;

    public function __construct(string $grpcUrl, LoggerInterface $logger)
    {
        $this->client = new CarRepositoryClient($grpcUrl);

        $this->setLogger($logger);
    }

    public function getMileages(AccessTokenAwareInterface $user, MileageFilter $filter): MileageDTOList
    {
        $response = $this->client->GetMileages($this->context($user), $filter);

        $current = $response->getMeta()?->getCurrent() ?? 1;
        $last = $response->getMeta()?->getLast() ?? 1;

        $items = new MileageDTOList($current, $last);
        foreach ($response->getMileages() as $item) {
            $items->add(MileageDTO::fromData($item));
        }

        $this->debug('gRPC response', ['expenses_cnt' => count($items)]);

        return $items;
    }

    /**
     * @throws \Twirp\Error
     */
    public function saveMileage(AccessTokenAwareInterface $user, MileageDTO $mileage): MileageDTO
    {
        $this->debug('Save mileage', ['data' => $mileage->toArray()]);

        $response = $this->client->SaveMileage($this->context($user), $mileage->reverse());
        $this->debug('gRPC response', ['mileage_id' => $response->getId()]);

        return MileageDTO::fromData($response);
    }

        /**
     * @throws \Twirp\Error
     */
    public function getServices(AccessTokenAwareInterface $user, ServiceFilter $filter): ServiceDTOList
    {
        $response = $this->client->GetServices($this->context($user), $filter);

        $current = $response->getMeta()?->getCurrent() ?? 1;
        $last = $response->getMeta()?->getLast() ?? 1;

        $orders = new ServiceDTOList($current, $last);
        foreach ($response->getServices() as $item) {
            $orders->add(ServiceDTO::fromData($item));
        }

        $this->debug('gRPC response', ['services_cnt' => count($orders)]);

        return $orders;
    }

    /**
     * @throws \Twirp\Error
     */
    public function findService(AccessTokenAwareInterface $user, int $id): ServiceDTO
    {
        $req = new IdRequest();
        $req->setId($id);

        $response = $this->client->FindService($this->context($user), $req);
        $this->debug('gRPC response', ['service_id' => $response->getId()]);

        return ServiceDTO::fromData($response);
    }

    /**
     * @throws \Twirp\Error
     */
    public function saveService(AccessTokenAwareInterface $user, ServiceDTO $service): ServiceDTO
    {
        $this->debug('Save service', ['data' => $service->toArray()]);

        $response = $this->client->SaveService($this->context($user), $service->reverse());
        $this->debug('gRPC response', ['service_id' => $response->getId()]);

        return ServiceDTO::fromData($response);
    }
}
