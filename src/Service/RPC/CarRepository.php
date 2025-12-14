<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 14.12.2025
 */

namespace TeleBot\Service\RPC;

use AutoNotes\Server\CarRepositoryClient;
use AutoNotes\Server\MileageFilter;
use Psr\Log\LoggerInterface;
use TeleBot\DTO\List\MileageDTOList;
use TeleBot\DTO\MileageDTO;
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
        // @phpstan-ignore foreach.nonIterable
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
}
