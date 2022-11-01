<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220617083247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attendees_event DROP FOREIGN KEY FK_9CEC52963C76898B');
        $this->addSql('ALTER TABLE attendees_user DROP FOREIGN KEY FK_A8ED86AD3C76898B');
        $this->addSql('CREATE TABLE event_user (event_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_92589AE271F7E88B (event_id), INDEX IDX_92589AE2A76ED395 (user_id), PRIMARY KEY(event_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE attendees');
        $this->addSql('DROP TABLE attendees_event');
        $this->addSql('DROP TABLE attendees_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attendees (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE attendees_event (attendees_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_9CEC52963C76898B (attendees_id), INDEX IDX_9CEC529671F7E88B (event_id), PRIMARY KEY(attendees_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE attendees_user (attendees_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A8ED86AD3C76898B (attendees_id), INDEX IDX_A8ED86ADA76ED395 (user_id), PRIMARY KEY(attendees_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE attendees_event ADD CONSTRAINT FK_9CEC52963C76898B FOREIGN KEY (attendees_id) REFERENCES attendees (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attendees_event ADD CONSTRAINT FK_9CEC529671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attendees_user ADD CONSTRAINT FK_A8ED86AD3C76898B FOREIGN KEY (attendees_id) REFERENCES attendees (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attendees_user ADD CONSTRAINT FK_A8ED86ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE event_user');
    }
}
