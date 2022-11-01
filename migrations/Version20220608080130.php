<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220608080130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attendees (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attendees_event (attendees_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_9CEC52963C76898B (attendees_id), INDEX IDX_9CEC529671F7E88B (event_id), PRIMARY KEY(attendees_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attendees_user (attendees_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A8ED86AD3C76898B (attendees_id), INDEX IDX_A8ED86ADA76ED395 (user_id), PRIMARY KEY(attendees_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, restaurant_id INT NOT NULL, comment LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526CB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, date DATETIME NOT NULL, places INT NOT NULL, meal ENUM(\'lunch\', \'dinner\') NOT NULL COMMENT \'(DC2Type:MealTimeType)\', theme VARCHAR(255) DEFAULT NULL, INDEX IDX_3BAE0AA7B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE food_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, picture VARCHAR(255) NOT NULL, INDEX IDX_16DB4F89B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, food_type_id INT NOT NULL, restorer_id INT NOT NULL, zone_id INT NOT NULL, name VARCHAR(255) NOT NULL, chef_name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, menu_text VARCHAR(255) DEFAULT NULL, main_picture VARCHAR(255) DEFAULT NULL, tripadvisor_link VARCHAR(255) DEFAULT NULL, website_link VARCHAR(255) DEFAULT NULL, facebook_link VARCHAR(255) DEFAULT NULL, instagram_link VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, siret VARCHAR(255) NOT NULL, billing_address VARCHAR(255) DEFAULT NULL, vat VARCHAR(255) DEFAULT NULL, menu_pdf VARCHAR(255) DEFAULT NULL, average_price INT DEFAULT NULL, phone_number VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_EB95123F8AD350AB (food_type_id), INDEX IDX_EB95123F29C3E95E (restorer_id), INDEX IDX_EB95123F9F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restorer (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, user_name VARCHAR(255) NOT NULL, birthday DATE NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, sex ENUM(\'female\', \'male\') NOT NULL COMMENT \'(DC2Type:SexType)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attendees_event ADD CONSTRAINT FK_9CEC52963C76898B FOREIGN KEY (attendees_id) REFERENCES attendees (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attendees_event ADD CONSTRAINT FK_9CEC529671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attendees_user ADD CONSTRAINT FK_A8ED86AD3C76898B FOREIGN KEY (attendees_id) REFERENCES attendees (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attendees_user ADD CONSTRAINT FK_A8ED86ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F8AD350AB FOREIGN KEY (food_type_id) REFERENCES food_type (id)');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F29C3E95E FOREIGN KEY (restorer_id) REFERENCES restorer (id)');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attendees_event DROP FOREIGN KEY FK_9CEC52963C76898B');
        $this->addSql('ALTER TABLE attendees_user DROP FOREIGN KEY FK_A8ED86AD3C76898B');
        $this->addSql('ALTER TABLE attendees_event DROP FOREIGN KEY FK_9CEC529671F7E88B');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F8AD350AB');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CB1E7706E');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7B1E7706E');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89B1E7706E');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F29C3E95E');
        $this->addSql('ALTER TABLE attendees_user DROP FOREIGN KEY FK_A8ED86ADA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F9F2C3FAB');
        $this->addSql('DROP TABLE attendees');
        $this->addSql('DROP TABLE attendees_event');
        $this->addSql('DROP TABLE attendees_user');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE food_type');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE restorer');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE zone');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
