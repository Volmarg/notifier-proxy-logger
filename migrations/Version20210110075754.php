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
        return 'Add placeholder discord webhook for messages without parent webhook';
    }

    public function up(Schema $schema) : void
    {
        // deleted is set to 1 to prevent ever seeing this placeholder in any place in code, gui etc.
        $this->addSql('
            INSERT INTO discord_webhook(username, webhook_url, description, webhook_name, deleted)
            VALUES("placeholder", "placeholder", "Placeholder for messages with deleted webhooks", "Placeholder for messages with deleted webhooks", 1)
        ');

    }

    public function down(Schema $schema) : void
    {
        // no going back
    }
}
