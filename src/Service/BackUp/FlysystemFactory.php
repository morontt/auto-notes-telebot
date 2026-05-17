<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 17.05.2026
 * Time: 08:11
 */

namespace TeleBot\Service\BackUp;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;

class FlysystemFactory
{
    public function __construct(
        private string $region,
        private string $endpoint,
        private string $backetName,
        private string $accessKey,
        private string $secret,
    ) {
    }

    public function createFlysystem(): Filesystem
    {
        $client = new S3Client([
            'region' => $this->region,
            'use_path_style_endpoint' => false,
            'credentials' => [
                'key' => $this->accessKey,
                'secret' => $this->secret,
            ],
            'endpoint' => 'https://' . $this->endpoint,
        ]);

        $adapter = new AwsS3V3Adapter(
            $client,
            $this->backetName,
            'autonotes'
        );

        return new Filesystem($adapter);
    }
}
