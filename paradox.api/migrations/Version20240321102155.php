<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321102155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD tenant_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64960D47263 FOREIGN KEY (tenant_id_id) REFERENCES tenant (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64960D47263 ON user (tenant_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64960D47263');
        $this->addSql('DROP INDEX IDX_8D93D64960D47263 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP tenant_id_id');
    }
}
