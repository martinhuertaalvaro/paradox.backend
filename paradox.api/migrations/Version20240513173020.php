<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513173020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE device (id INT AUTO_INCREMENT NOT NULL, tenant_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, INDEX IDX_92FB68E60D47263 (tenant_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device_user (device_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4AA037394A4C7D4 (device_id), INDEX IDX_4AA0373A76ED395 (user_id), PRIMARY KEY(device_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, tenant_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C4E0A61F60D47263 (tenant_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_user (team_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5C722232296CD8AE (team_id), INDEX IDX_5C722232A76ED395 (user_id), PRIMARY KEY(team_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_device (team_id INT NOT NULL, device_id INT NOT NULL, INDEX IDX_7F34F157296CD8AE (team_id), INDEX IDX_7F34F15794A4C7D4 (device_id), PRIMARY KEY(team_id, device_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tenant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, code VARCHAR(20) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, tenant_id_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, surname VARCHAR(255) DEFAULT NULL, birthdate DATETIME DEFAULT NULL, active TINYINT(1) NOT NULL, city VARCHAR(255) DEFAULT NULL, workfield VARCHAR(255) DEFAULT NULL, registerdate DATETIME NOT NULL, friends LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D64960D47263 (tenant_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E60D47263 FOREIGN KEY (tenant_id_id) REFERENCES tenant (id)');
        $this->addSql('ALTER TABLE device_user ADD CONSTRAINT FK_4AA037394A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE device_user ADD CONSTRAINT FK_4AA0373A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F60D47263 FOREIGN KEY (tenant_id_id) REFERENCES tenant (id)');
        $this->addSql('ALTER TABLE team_user ADD CONSTRAINT FK_5C722232296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_user ADD CONSTRAINT FK_5C722232A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_device ADD CONSTRAINT FK_7F34F157296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_device ADD CONSTRAINT FK_7F34F15794A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64960D47263 FOREIGN KEY (tenant_id_id) REFERENCES tenant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68E60D47263');
        $this->addSql('ALTER TABLE device_user DROP FOREIGN KEY FK_4AA037394A4C7D4');
        $this->addSql('ALTER TABLE device_user DROP FOREIGN KEY FK_4AA0373A76ED395');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F60D47263');
        $this->addSql('ALTER TABLE team_user DROP FOREIGN KEY FK_5C722232296CD8AE');
        $this->addSql('ALTER TABLE team_user DROP FOREIGN KEY FK_5C722232A76ED395');
        $this->addSql('ALTER TABLE team_device DROP FOREIGN KEY FK_7F34F157296CD8AE');
        $this->addSql('ALTER TABLE team_device DROP FOREIGN KEY FK_7F34F15794A4C7D4');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64960D47263');
        $this->addSql('DROP TABLE device');
        $this->addSql('DROP TABLE device_user');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE team_user');
        $this->addSql('DROP TABLE team_device');
        $this->addSql('DROP TABLE tenant');
        $this->addSql('DROP TABLE `user`');
    }
}
