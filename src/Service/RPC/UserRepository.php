<?php
/**
 * User: morontt
 * Date: 02.03.2025
 * Time: 19:25
 */

declare(strict_types=1);

namespace TeleBot\Service\RPC;

use AutoNotes\Server\UserRepositoryClient;
use Google\Protobuf\GPBEmpty;
use Psr\Log\LoggerInterface;
use TeleBot\DTO\CarDTO;
use TeleBot\DTO\CurrencyDTO;
use TeleBot\DTO\List\CarDTOList;
use TeleBot\DTO\List\CurrencyDTOList;
use TeleBot\DTO\UserSettingsDTO;
use TeleBot\LogTrait;
use TeleBot\Security\AccessTokenAwareInterface;

class UserRepository extends AbstractRepository
{
    use LogTrait;

    private UserRepositoryClient $client;

    public function __construct(string $grpcUrl, LoggerInterface $logger)
    {
        $this->client = new UserRepositoryClient($grpcUrl);

        $this->setLogger($logger);
    }

    /**
     * @throws \Twirp\Error
     */
    public function getCars(AccessTokenAwareInterface $user): CarDTOList
    {
        $response = $this->client->GetCars($this->context($user), new GPBEmpty());

        $cars = new CarDTOList();
        foreach ($response->getCars() as $item) {
            $cars->add(CarDTO::fromData($item));
        }

        $this->debug('gRPC response', ['cars_cnt' => count($cars)]);

        return $cars;
    }

    /**
     * @throws \Twirp\Error
     */
    public function getDefaultCurrency(AccessTokenAwareInterface $user): ?CurrencyDTO
    {
        $result = null;

        $response = $this->client->GetDefaultCurrency($this->context($user), new GPBEmpty());
        $found = $response->getFound();
        $this->debug('gRPC response', ['found' => $found]);

        if ($found) {
            if ($response->hasCurrency()) {
                $result = CurrencyDTO::fromData($response->getCurrency());
            } else {
                $this->error('gRPC response without currency but found=true');
            }
        }

        return $result;
    }

    /**
     * @throws \Twirp\Error
     */
    public function getCurrencies(AccessTokenAwareInterface $user): CurrencyDTOList
    {
        $response = $this->client->GetCurrencies($this->context($user), new GPBEmpty());

        $currencies = new CurrencyDTOList();
        foreach ($response->getCurrencies() as $item) {
            $currencies->add(CurrencyDTO::fromData($item));
        }

        $this->debug('gRPC response', ['currencies_cnt' => count($currencies)]);

        return $currencies;
    }

    /**
     * @throws \Twirp\Error
     */
    public function getUserSettings(AccessTokenAwareInterface $user): ?UserSettingsDTO
    {
        $response = $this->client->GetUserSettings($this->context($user), new GPBEmpty());

        return UserSettingsDTO::fromData($response);
    }

    /**
     * @throws \Twirp\Error
     */
    public function saveUserSettings(AccessTokenAwareInterface $user, UserSettingsDTO $userSettings): UserSettingsDTO
    {
        $this->debug('Save user settings', ['data' => $userSettings->toArray()]);
        $response = $this->client->SaveUserSettings($this->context($user), $userSettings->reverse());

        return UserSettingsDTO::fromData($response);
    }
}
