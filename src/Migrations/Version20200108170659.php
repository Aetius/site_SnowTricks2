<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200108170659 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email_id INT DEFAULT NULL, email_link_token_id INT DEFAULT NULL, login VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_activate TINYINT(1) DEFAULT \'0\' NOT NULL, last_update DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649A832C1C9 (email_id), UNIQUE INDEX UNIQ_8D93D6497E4394D8 (email_link_token_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_link_token (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, user INT NOT NULL, action JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(150) NOT NULL, is_verified TINYINT(1) DEFAULT \'0\' NOT NULL, date_creation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A832C1C9 FOREIGN KEY (email_id) REFERENCES email (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497E4394D8 FOREIGN KEY (email_link_token_id) REFERENCES email_link_token (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497E4394D8');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A832C1C9');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE email_link_token');
        $this->addSql('DROP TABLE email');
    }
}
