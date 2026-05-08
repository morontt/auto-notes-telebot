<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250627175646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE region_codes (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', region_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(3) NOT NULL, INDEX IDX_5DC9C25598260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regions (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', name VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE region_codes ADD CONSTRAINT FK_5DC9C25598260155 FOREIGN KEY (region_id) REFERENCES regions (id) ON DELETE RESTRICT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE region_codes DROP FOREIGN KEY FK_5DC9C25598260155');
        $this->addSql('DROP TABLE region_codes');
        $this->addSql('DROP TABLE regions');
    }
}
