<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200114151954 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');



        $this->addSql('ALTER TABLE user ADD email VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL, ADD email_is_valid TINYINT(1) DEFAULT \'0\' NOT NULL, DROP email_id, CHANGE last_update updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE email_link_token CHANGE action action VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE email_link_token CHANGE action action JSON NOT NULL');
        $this->addSql('ALTER TABLE user ADD email_id INT DEFAULT NULL, DROP email, DROP created_at, DROP email_is_valid, CHANGE updated_at last_update DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A832C1C9 FOREIGN KEY (email_id) REFERENCES email (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A832C1C9 ON user (email_id)');
    }
}
