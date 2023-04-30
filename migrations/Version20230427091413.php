<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230427091413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE applications (id INT AUTO_INCREMENT NOT NULL, user INT NOT NULL, offer INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post DROP users_candidature');
        $this->addSql('ALTER TABLE user DROP candidatures');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE applications');
        $this->addSql('ALTER TABLE post ADD users_candidature LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE user ADD candidatures LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\'');
    }
}
