<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200107170516 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE token_reset_password ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user DROP INDEX FK_8D93D649C3A13C85, ADD UNIQUE INDEX UNIQ_8D93D649C3A13C85 (token_reset_password_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE token_reset_password DROP user_id');
        $this->addSql('ALTER TABLE user DROP INDEX UNIQ_8D93D649C3A13C85, ADD INDEX FK_8D93D649C3A13C85 (token_reset_password_id)');
    }
}
