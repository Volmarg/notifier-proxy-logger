<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220117154133 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mail_attachment (id INT AUTO_INCREMENT NOT NULL, email_id INT NOT NULL, created DATETIME NOT NULL, path VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, file_type VARCHAR(50) NOT NULL, INDEX IDX_AD9C3347A832C1C9 (email_id), UNIQUE INDEX unique_file_name (file_name, email_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mail_attachment ADD CONSTRAINT FK_AD9C3347A832C1C9 FOREIGN KEY (email_id) REFERENCES mail (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE mail_attachment');
    }
}
