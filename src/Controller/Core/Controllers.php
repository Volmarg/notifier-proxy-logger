<?php

namespace App\Controller\Core;

use App\Controller\Modules\Discord\DiscordWebhookController;
use App\Controller\Modules\Mailing\MailController;
use App\Controller\System\SecurityController;
use App\Controller\UserController;

class Controllers
{
    /**
     * @var SecurityController $securityController
     */
    private SecurityController $securityController;

    /**
     * @var UserController $userController
     */
    private UserController $userController;

    /**
     * @var MailController $mailingController
     */
    private MailController $mailingController;

    /**
     * @var DiscordWebhookController $discordWebhookController
     */
    private DiscordWebhookController $discordWebhookController;

    /**
     * @return SecurityController
     */
    public function getSecurityController(): SecurityController
    {
        return $this->securityController;
    }

    /**
     * @param SecurityController $securityController
     */
    public function setSecurityController(SecurityController $securityController): void
    {
        $this->securityController = $securityController;
    }

    /**
     * @return UserController
     */
    public function getUserController(): UserController
    {
        return $this->userController;
    }

    /**
     * @param UserController $userController
     */
    public function setUserController(UserController $userController): void
    {
        $this->userController = $userController;
    }

    /**
     * @return MailController
     */
    public function getMailingController(): MailController
    {
        return $this->mailingController;
    }

    /**
     * @param MailController $mailingController
     */
    public function setMailingController(MailController $mailingController): void
    {
        $this->mailingController = $mailingController;
    }

    /**
     * @return DiscordWebhookController
     */
    public function getDiscordWebhookController(): DiscordWebhookController
    {
        return $this->discordWebhookController;
    }

    /**
     * @param DiscordWebhookController $discordWebhookController
     */
    public function setDiscordWebhookController(DiscordWebhookController $discordWebhookController): void
    {
        $this->discordWebhookController = $discordWebhookController;
    }

}