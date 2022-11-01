<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220704140918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD time VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F29C3E95E');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F29C3E95E FOREIGN KEY (restorer_id) REFERENCES restorer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP time');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F29C3E95E');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F29C3E95E FOREIGN KEY (restorer_id) REFERENCES restorer (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
