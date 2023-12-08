<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231208212029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE signalement (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, personnel_id INT DEFAULT NULL, observation LONGTEXT NOT NULL, date_signal DATETIME NOT NULL, INDEX IDX_F4B551146B899279 (patient_id), INDEX IDX_F4B551141C109075 (personnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B551146B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B551141C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)');
        $this->addSql('ALTER TABLE `signal` DROP FOREIGN KEY FK_740C95F51C109075');
        $this->addSql('ALTER TABLE `signal` DROP FOREIGN KEY FK_740C95F56B899279');
        $this->addSql('DROP TABLE `signal`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `signal` (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, personnel_id INT DEFAULT NULL, observation LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_signal DATETIME NOT NULL, INDEX IDX_740C95F56B899279 (patient_id), INDEX IDX_740C95F51C109075 (personnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `signal` ADD CONSTRAINT FK_740C95F51C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)');
        $this->addSql('ALTER TABLE `signal` ADD CONSTRAINT FK_740C95F56B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B551146B899279');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B551141C109075');
        $this->addSql('DROP TABLE signalement');
    }
}
