<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210110075754 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE discord_message (
                id INT AUTO_INCREMENT NOT NULL, 
                discord_webhook_id INT NOT NULL, 
                message_content LONGTEXT NOT NULL, 
                message_title VARCHAR(250) NOT NULL, 
                status VARCHAR(100) NOT NULL, 
                source VARCHAR(50) NOT NULL, 
                created DATETIME NOT NULL, 
                INDEX IDX_F220142443246941 (discord_webhook_id), PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            ALTER TABLE discord_message ADD CONSTRAINT FK_F220142443246941 FOREIGN KEY (discord_webhook_id) REFERENCES discord_webhook (id)
        ');

        $this->addSql('
            CREATE UNIQUE INDEX unique_name ON discord_webhook (webhook_name)
        ');

    }

    public function down(Schema $schema) : void
    {
        // no going back
    }
}
