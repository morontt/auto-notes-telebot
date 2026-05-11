<?php

declare(strict_types=1);

namespace AutoNotes\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260511175006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fuel_types DROP FOREIGN KEY FK_3390D69A727ACA70');
        $this->addSql('ALTER TABLE fuel_types ADD CONSTRAINT FK_3390D69A727ACA70 FOREIGN KEY (parent_id) REFERENCES fuel_types (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fuel_types DROP FOREIGN KEY FK_3390D69A727ACA70');
        $this->addSql('ALTER TABLE fuel_types ADD CONSTRAINT FK_3390D69A727ACA70 FOREIGN KEY (parent_id) REFERENCES fuel_types (id) ON UPDATE NO ACTION');
    }
}
