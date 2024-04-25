<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240419122329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE team_device (team_id INT NOT NULL, device_id INT NOT NULL, INDEX IDX_7F34F157296CD8AE (team_id), INDEX IDX_7F34F15794A4C7D4 (device_id), PRIMARY KEY(team_id, device_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE team_device ADD CONSTRAINT FK_7F34F157296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_device ADD CONSTRAINT FK_7F34F15794A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team_device DROP FOREIGN KEY FK_7F34F157296CD8AE');
        $this->addSql('ALTER TABLE team_device DROP FOREIGN KEY FK_7F34F15794A4C7D4');
        $this->addSql('DROP TABLE team_device');
    }
}
