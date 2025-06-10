<?php

/**
 * User: morontt
 * Date: 01.03.2025
 * Time: 10:23
 */

namespace Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TeleBot\Repository\AccessTokenRepository;
use TeleBot\Service\TokenStorage;

#[CoversClass(TokenStorage::class)]
class TokenStorageTest extends TestCase
{
    public function testEncryption(): void
    {
        $repositoryMock = $this->createMock(AccessTokenRepository::class);

        $storage = new TokenStorage($repositoryMock, md5((string)time()));

        $sourceString = 'Hello, Alice!';
        $enc = $storage->encrypt($sourceString);

        $this->assertSame($sourceString, $storage->decrypt($enc));
    }
}
