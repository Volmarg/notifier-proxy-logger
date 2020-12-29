<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130043911 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
                CREATE TABLE IF NOT EXISTS `user` (
                id INT AUTO_INCREMENT NOT NULL, 
                username VARCHAR(180) NOT NULL, 
                roles JSON NOT NULL, 
                password VARCHAR(255) NOT NULL, 
                avatar LONGTEXT DEFAULT NULL, 
                displayed_username VARCHAR(100) DEFAULT NULL, 
                UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), 
                PRIMARY KEY(id)
              ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
          ');

        $this->addSql('
                CREATE TABLE IF NOT EXISTS mail (
                id INT AUTO_INCREMENT NOT NULL, 
                from_email VARCHAR(255) NOT NULL, 
                subject VARCHAR(255) NOT NULL, 
                body LONGTEXT NOT NULL, 
                status VARCHAR(100) NOT NULL, 
                created DATETIME NOT NULL, 
                source VARCHAR(50) NOT NULL, 
                to_emails JSON NOT NULL, 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
                CREATE TABLE IF NOT EXISTS discord_webhook (
                    id INT AUTO_INCREMENT NOT NULL, 
                    username VARCHAR(255) NOT NULL, 
                    webhook_url LONGTEXT NOT NULL, 
                    description LONGTEXT NOT NULL, 
                    webhook_name VARCHAR(255) NOT NULL,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

    }

    public function down(Schema $schema) : void
    {
        // no going back
    }
}
