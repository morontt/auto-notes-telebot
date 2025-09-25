<?php
/**
 * User: morontt
 * Date: 26.02.2025
 * Time: 18:31
 */

declare(strict_types=1);

namespace TeleBot\Service;

use DateTime;
use TeleBot\Entity\AccessToken;
use TeleBot\Repository\AccessTokenRepository;
use TeleBot\Utils\Jwt;

class TokenStorage
{
    private const CIPHER = 'AES-256-CFB';

    private AccessTokenRepository $repository;
    private string $secret;

    public function __construct(AccessTokenRepository $repository, string $secret)
    {
        $this->repository = $repository;
        $this->secret = $secret;
    }

    public function setToken(string $token, int $userId): void
    {
        $encryptedToken = $this->encrypt($token);
        $tokenData = Jwt::claims($token);

        /* @var AccessToken $tokenObj */
        $tokenObj = $this->repository->findOneBy(['userId' => $userId]);
        if ($tokenObj) {
            $tokenObj
                ->setToken($encryptedToken)
                ->setExpiredAt($tokenData->expiredAt)
                ->setUpdatedAt(new DateTime())
            ;
        } else {
            $tokenObj = new AccessToken();
            $tokenObj
                ->setToken($encryptedToken)
                ->setExpiredAt($tokenData->expiredAt)
                ->setUserId($userId)
            ;
        }

        $this->repository->save($tokenObj);
    }

    public function encrypt(string $value): string
    {
        $salt = random_bytes(6);

        return base64_encode($salt) .
            base64_encode(openssl_encrypt($value, self::CIPHER, $this->secret, 0, $this->getVector($salt)));
    }

    public function decrypt(string $value): string
    {
        $parts = [
            substr($value, 0, 8),
            substr($value, 8)
        ];

        $salt = base64_decode($parts[0]);

        return openssl_decrypt(base64_decode($parts[1]), self::CIPHER, $this->secret, 0, $this->getVector($salt));
    }

    private function getVector(string $salt): string
    {
        return substr(hash('sha1', $this->secret . $salt, true), 0, openssl_cipher_iv_length(self::CIPHER));
    }
}
