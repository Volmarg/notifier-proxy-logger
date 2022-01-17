<?php

namespace App\Controller\Modules\Mailing\Type;

use App\Entity\Modules\Mailing\MailAccount;

/**
 * Generic logic for mail types handlers
 */
class MailTypeController
{

    /**
     * Will build the mailer (MAILER_DSN) connection string used internally by symfony
     *
     * @param MailAccount $mailAccount
     * @return string
     */
    public function buildSymfonyMailerDsnConnectionString(MailAccount $mailAccount): string
    {
        $dsnConnectionString = "{$mailAccount->getClient()}://{$mailAccount->getLogin()}:{$mailAccount->getPassword()}@localhost";
        return $dsnConnectionString;
    }

}