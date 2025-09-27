<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 27.06.2025
 * Time: 19:54
 */

namespace TeleBot\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TeleBot\Entity\Region;
use TeleBot\Entity\RegionCode;

class ImportRegionCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('telebot:regions:import')
            ->setDescription('Import regions')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = simplexml_load_string(file_get_contents(__DIR__ . '/../../var/data/regions_table.xml'));
        foreach ($data->tbody->tr as $row) {
            $codes = array_map('trim', explode(',', (string)$row->td[0]->div->p));

            $xmlObj = $row->td[1]->div->p;
            $regionName = trim((string)($xmlObj->span ?? $xmlObj));

            $region = new Region();
            $region->setName($regionName);

            $this->em->persist($region);
            $this->em->flush();

            foreach ($codes as $code) {
                $regionCode = new RegionCode();
                $regionCode
                    ->setCode($code)
                    ->setRegion($region)
                ;

                $this->em->persist($regionCode);
                $this->em->flush();
            }

            $output->writeln($region->getName());
        }

        return 0;
    }
}
