<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240418205302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device ADD tenant_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E60D47263 FOREIGN KEY (tenant_id_id) REFERENCES tenant (id)');
        $this->addSql('CREATE INDEX IDX_92FB68E60D47263 ON device (tenant_id_id)');
        $this->addSql('ALTER TABLE team ADD tenant_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F60D47263 FOREIGN KEY (tenant_id_id) REFERENCES tenant (id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F60D47263 ON team (tenant_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68E60D47263');
        $this->addSql('DROP INDEX IDX_92FB68E60D47263 ON device');
        $this->addSql('ALTER TABLE device DROP tenant_id_id');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F60D47263');
        $this->addSql('DROP INDEX IDX_C4E0A61F60D47263 ON team');
        $this->addSql('ALTER TABLE team DROP tenant_id_id');
    }
}
