<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230429100050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, workplace VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, schedule VARCHAR(50) NOT NULL, salary VARCHAR(50) NOT NULL, published_at DATETIME NOT NULL, is_validated TINYINT(1) NOT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, firstname VARCHAR(60) NOT NULL, email VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, enterprise VARCHAR(255) DEFAULT NULL, enterprise_adress VARCHAR(255) DEFAULT NULL, validated_account TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX UNIQ_F7C966F0A76ED395 ON applications');
        $this->addSql('DROP INDEX UNIQ_F7C966F04B89032C ON applications');
        $this->addSql('ALTER TABLE applications ADD user INT NOT NULL, ADD offer INT NOT NULL, ADD is_validated TINYINT(1) DEFAULT NULL, ADD workplace VARCHAR(255) NOT NULL, ADD title VARCHAR(255) NOT NULL, ADD username VARCHAR(50) NOT NULL, ADD userfirstname VARCHAR(60) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD recruteur VARCHAR(255) NOT NULL, ADD cv VARCHAR(255) NOT NULL, DROP post_id, DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE applications ADD post_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, DROP user, DROP offer, DROP is_validated, DROP workplace, DROP title, DROP username, DROP userfirstname, DROP email, DROP recruteur, DROP cv');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7C966F0A76ED395 ON applications (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7C966F04B89032C ON applications (post_id)');
    }
}
