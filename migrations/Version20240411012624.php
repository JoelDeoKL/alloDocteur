<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240411012624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche CHANGE date_naissance date_naissance VARCHAR(255) NOT NULL, CHANGE date_entre date_entre VARCHAR(255) NOT NULL, CHANGE date_sortie date_sortie VARCHAR(255) NOT NULL, CHANGE date_creation date_creation VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche CHANGE date_naissance date_naissance DATE NOT NULL, CHANGE date_entre date_entre DATETIME NOT NULL, CHANGE date_sortie date_sortie DATETIME NOT NULL, CHANGE date_creation date_creation DATETIME NOT NULL');
    }
}
