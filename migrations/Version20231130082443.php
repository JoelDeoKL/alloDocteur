<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130082443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE diagnostic (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, personnel_id INT DEFAULT NULL, nom_diagnostic VARCHAR(255) NOT NULL, description_diagnostic LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_FA7C88896B899279 (patient_id), INDEX IDX_FA7C88891C109075 (personnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dossier (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, description_dossier LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_3D48E0376B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examen (id INT AUTO_INCREMENT NOT NULL, personnel_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, nom_examen VARCHAR(255) NOT NULL, description_examen LONGTEXT NOT NULL, date_examen DATETIME NOT NULL, INDEX IDX_514C8FEC1C109075 (personnel_id), INDEX IDX_514C8FEC6B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche (id INT AUTO_INCREMENT NOT NULL, dossier_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, personnel_id INT DEFAULT NULL, date_naissance DATE NOT NULL, adresse_patient VARCHAR(255) NOT NULL, date_entre DATETIME NOT NULL, date_sortie DATETIME NOT NULL, nom_conjoint VARCHAR(255) DEFAULT NULL, service VARCHAR(255) DEFAULT NULL, observation LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_4C13CC78611C0C56 (dossier_id), INDEX IDX_4C13CC786B899279 (patient_id), INDEX IDX_4C13CC781C109075 (personnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ordonnance (id INT AUTO_INCREMENT NOT NULL, personnel_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, ordannance LONGTEXT NOT NULL, date_ordonnance DATETIME NOT NULL, INDEX IDX_924B326C1C109075 (personnel_id), INDEX IDX_924B326C6B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, personnel_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom_patient VARCHAR(255) NOT NULL, postnom_patient VARCHAR(255) NOT NULL, prenom_patieint VARCHAR(255) NOT NULL, description_patient LONGTEXT NOT NULL, age INT NOT NULL, telephone VARCHAR(255) NOT NULL, matricule VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, UNIQUE INDEX UNIQ_1ADAD7EBE7927C74 (email), INDEX IDX_1ADAD7EB1C109075 (personnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personnel (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom_personnel VARCHAR(255) NOT NULL, postnom_personnel VARCHAR(255) NOT NULL, prenom_personnel VARCHAR(255) NOT NULL, description_personnel LONGTEXT NOT NULL, fonction VARCHAR(255) NOT NULL, telephone_personnel VARCHAR(255) NOT NULL, specialite VARCHAR(255) NOT NULL, num_ordre VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, UNIQUE INDEX UNIQ_A6BCF3DEE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `signal` (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, personnel_id INT DEFAULT NULL, observation LONGTEXT NOT NULL, date_signal DATETIME NOT NULL, INDEX IDX_740C95F56B899279 (patient_id), INDEX IDX_740C95F51C109075 (personnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE diagnostic ADD CONSTRAINT FK_FA7C88896B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE diagnostic ADD CONSTRAINT FK_FA7C88891C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)');
        $this->addSql('ALTER TABLE dossier ADD CONSTRAINT FK_3D48E0376B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE examen ADD CONSTRAINT FK_514C8FEC1C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)');
        $this->addSql('ALTER TABLE examen ADD CONSTRAINT FK_514C8FEC6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE fiche ADD CONSTRAINT FK_4C13CC78611C0C56 FOREIGN KEY (dossier_id) REFERENCES dossier (id)');
        $this->addSql('ALTER TABLE fiche ADD CONSTRAINT FK_4C13CC786B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE fiche ADD CONSTRAINT FK_4C13CC781C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326C1C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326C6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB1C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)');
        $this->addSql('ALTER TABLE `signal` ADD CONSTRAINT FK_740C95F56B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE `signal` ADD CONSTRAINT FK_740C95F51C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE diagnostic DROP FOREIGN KEY FK_FA7C88896B899279');
        $this->addSql('ALTER TABLE diagnostic DROP FOREIGN KEY FK_FA7C88891C109075');
        $this->addSql('ALTER TABLE dossier DROP FOREIGN KEY FK_3D48E0376B899279');
        $this->addSql('ALTER TABLE examen DROP FOREIGN KEY FK_514C8FEC1C109075');
        $this->addSql('ALTER TABLE examen DROP FOREIGN KEY FK_514C8FEC6B899279');
        $this->addSql('ALTER TABLE fiche DROP FOREIGN KEY FK_4C13CC78611C0C56');
        $this->addSql('ALTER TABLE fiche DROP FOREIGN KEY FK_4C13CC786B899279');
        $this->addSql('ALTER TABLE fiche DROP FOREIGN KEY FK_4C13CC781C109075');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326C1C109075');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326C6B899279');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB1C109075');
        $this->addSql('ALTER TABLE `signal` DROP FOREIGN KEY FK_740C95F56B899279');
        $this->addSql('ALTER TABLE `signal` DROP FOREIGN KEY FK_740C95F51C109075');
        $this->addSql('DROP TABLE diagnostic');
        $this->addSql('DROP TABLE dossier');
        $this->addSql('DROP TABLE examen');
        $this->addSql('DROP TABLE fiche');
        $this->addSql('DROP TABLE ordonnance');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE personnel');
        $this->addSql('DROP TABLE `signal`');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
