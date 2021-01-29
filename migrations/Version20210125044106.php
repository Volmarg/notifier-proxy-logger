<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210125044106 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE mail_account (
                id INT AUTO_INCREMENT NOT NULL, 
                client VARCHAR(100) NOT NULL, 
                name VARCHAR(100) NOT NULL, 
                login VARCHAR(100) NOT NULL, 
                password VARCHAR(100) NOT NULL, 
                CONSTRAINT `unique_name` UNIQUE(name),  
                PRIMARY KEY(id)
          ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema) : void
    {
        // no going back
    }
}
