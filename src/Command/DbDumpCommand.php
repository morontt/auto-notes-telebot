<?php

namespace TeleBot\Command;

use DateInterval;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use TeleBot\Service\BackupService;

#[AsCommand(
    name: 'telebot:db-dump',
    description: 'Create dump of database',
)]
class DbDumpCommand extends Command
{
    /**
     * @var array<int, array<string, mixed>>
     */
    private array $connectionParams;

    public function __construct(
        ManagerRegistry $registry,
        private BackupService $backupService,
    ) {
        parent::__construct();

        /** @var \Doctrine\DBAL\Connection */
        $defaultConnection = $registry->getConnection();
        /** @var \Doctrine\DBAL\Connection */
        $mainConnection = $registry->getConnection('main');

        $this->connectionParams = [
            $defaultConnection->getParams(),
            $mainConnection->getParams(),
        ];
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->clearOldDumps();

        foreach ($this->connectionParams as $params) {
            $this->createDump($params, $output);
        }

        return Command::SUCCESS;
    }

    /**
     * @param array<string, mixed> $params
     */
    private function createDump(array $params, OutputInterface $output): void
    {
        $dumpPath = $this->getFilename($params['dbname']);

        $process = Process::fromShellCommandline(
            sprintf(
                'mysqldump -h %s -P %s -u %s --password=%s %s | gzip -9 > %s',
                $params['host'],
                $params['port'],
                $params['user'],
                escapeshellarg($params['password']),
                $params['dbname'],
                $dumpPath
            )
        );
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        $output->writeln(
            sprintf(
                '<info>Database dump created: <comment>%s</comment>, %dKB</info>',
                pathinfo($dumpPath, PATHINFO_BASENAME),
                (int)(filesize($dumpPath) / 1024)
            )
        );

        $this->backupService->upload($dumpPath, $this->getBackupPath($dumpPath));
        unlink($dumpPath);
    }

    private function clearOldDumps(): void
    {
        $backedFiles = $this->backupService->filesByDir(BackupService::DUMPS_PATH);

        $from = (int)(
            (new DateTime())
            ->sub(new DateInterval('P' . BackupService::DUMPS_STORAGE_PERIOD . 'D'))
            ->format('U')
        );
        $delete = [];
        foreach ($backedFiles as $fileAttr) {
            if (
                ($ts = $fileAttr->lastModified())
                && $ts < $from
            ) {
                $delete[] = $fileAttr->path();
            }
        }

        foreach ($delete as $file) {
            $this->backupService->delete($file);
        }
    }

    private function getFilename(string $dbName): string
    {
        return sprintf(
            '%s/%s_%s.sql.gz',
            realpath(__DIR__ . '/../../var/temp'),
            (new DateTime())->format('YmdHi'),
            $dbName,
        );
    }

    private function getBackupPath(string $dumpPath): string
    {
        return BackupService::DUMPS_PATH . '/' . pathinfo($dumpPath, PATHINFO_BASENAME);
    }
}
