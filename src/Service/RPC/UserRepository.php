<?php

/**
 * User: morontt
 * Date: 02.03.2025
 * Time: 19:25
 */

namespace TeleBot\Service\RPC;

use AutoNotes\Server\UserRepositoryClient;
use Google\Protobuf\GPBEmpty;
use Psr\Log\LoggerInterface;
use RuntimeException;
use TeleBot\DTO\CarDTO;
use TeleBot\DTO\CurrencyDTO;
use TeleBot\DTO\UserSettingsDTO;
use TeleBot\Security\AccessTokenAwareInterface;
use Twirp\Error;

class UserRepository extends AbstractRepository
{
    private UserRepositoryClient $client;

    public function __construct(string $grpcUrl, private readonly LoggerInterface $logger)
    {
        $this->client = new UserRepositoryClient($grpcUrl);
    }

    /**
     * @return CarDTO[]
     */
    public function getCars(AccessTokenAwareInterface $user): array
    {
        $result = [];
        try {
            $response = $this->client->GetCars($this->context($user), new GPBEmpty());
            $cars = $response->getCars();
            $this->logger->debug('gRPC response', ['cars_cnt' => count($cars)]);

            foreach ($cars as $item) {
                $result[] = CarDTO::fromData($item);
            }
        } catch (Error $e) {
            $this->logger->error('gRPC error', [
                'error' => $e,
            ]);
        }

        return $result;
    }

    public function getDefaultCurrency(AccessTokenAwareInterface $user): ?CurrencyDTO
    {
        $result = null;
        try {
            $response = $this->client->GetDefaultCurrency($this->context($user), new GPBEmpty());
            $found = $response->getFound();
            $this->logger->debug('gRPC response', ['found' => $found]);

            if ($found) {
                if ($response->hasCurrency()) {
                    $result = CurrencyDTO::fromData($response->getCurrency());
                } else {
                    $this->logger->error('gRPC response without currency but found=true');
                }
            }
        } catch (Error $e) {
            $this->logger->error('gRPC error', [
                'error' => $e,
            ]);
        }

        return $result;
    }

    /**
     * @return CurrencyDTO[]
     */
    public function getCurrencies(AccessTokenAwareInterface $user): array
    {
        $result = [];
        try {
            $response = $this->client->GetCurrencies($this->context($user), new GPBEmpty());
            $currencies = $response->getCurrencies();
            $this->logger->debug('gRPC response', ['currencies_cnt' => count($currencies)]);

            foreach ($currencies as $item) {
                $result[] = CurrencyDTO::fromData($item);
            }
        } catch (Error $e) {
            $this->logger->error('gRPC error', [
                'error' => $e,
            ]);
        }

        return $result;
    }

    public function getUserSettings(AccessTokenAwareInterface $user): ?UserSettingsDTO
    {
        $result = null;
        try {
            $response = $this->client->GetUserSettings($this->context($user), new GPBEmpty());

            $result = UserSettingsDTO::fromData($response);
        } catch (Error $e) {
            $this->logger->error('gRPC error', [
                'error' => $e,
            ]);
        }

        return $result;
    }

    public function saveUserSettings(AccessTokenAwareInterface $user, UserSettingsDTO $userSettings): UserSettingsDTO
    {
        try {
            $response = $this->client->SaveUserSettings($this->context($user), $userSettings->reverse());

            return UserSettingsDTO::fromData($response);
        } catch (Error $e) {
            $this->logger->error('gRPC error', [
                'error' => $e,
            ]);
        }

        throw new RuntimeException('Failed to save user settings');
    }
}
