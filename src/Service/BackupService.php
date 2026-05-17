<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 17.05.2026
 * Time: 08:09
 */

namespace TeleBot\Service;

use League\Flysystem\FilesystemOperator;
use League\Flysystem\StorageAttributes;
use TeleBot\Service\BackUp\FlysystemFactory;
use Traversable;

class BackupService
{
    public const DUMPS_PATH = '/db_dumps';
    public const DUMPS_STORAGE_PERIOD = 14;

    private ?FilesystemOperator $flySystem = null;

    public function __construct(private FlysystemFactory $flysystemFactory)
    {
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public function fileExists(string $remotePath): bool
    {
        return $this->getFlySystem()->fileExists($remotePath);
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public function upload(string $localPath, string $remotePath): void
    {
        $fp = fopen($localPath, 'rb');
        $this->getFlySystem()->writeStream($remotePath, $fp);
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public function delete(string $path): void
    {
        $this->getFlySystem()->delete($path);
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     *
     * @return \League\Flysystem\DirectoryListing<StorageAttributes>
     */
    public function filesByDir(string $dir): Traversable
    {
        return $this
            ->getFlySystem()
            ->listContents($dir)
            ->filter(fn (StorageAttributes $attributes) => $attributes->isFile())
        ;
    }

    private function getFlySystem(): FilesystemOperator
    {
        if (is_null($this->flySystem)) {
            $this->flySystem = $this->flysystemFactory->createFlysystem();
        }

        return $this->flySystem;
    }
}
