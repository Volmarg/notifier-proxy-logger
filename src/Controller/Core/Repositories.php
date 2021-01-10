<?php


namespace App\Controller\Core;


use App\Repository\Modules\Discord\DiscordMessageRepository;
use App\Repository\Modules\Discord\DiscordWebhookRepository;
use App\Repository\Modules\Mailing\MailRepository;
use App\Repository\UserRepository;

class Repositories
{
    /**
     * @var UserRepository $userRepository
     */
    private UserRepository $userRepository;

    /**
     * @var MailRepository $mailRepository
     */
    private MailRepository $mailRepository;

    /**
     * @var DiscordWebhookRepository $discordWebhookRepository
     */
    private DiscordWebhookRepository $discordWebhookRepository;

    /**
     * @var DiscordMessageRepository $discordMessageRepository
     */
    private DiscordMessageRepository $discordMessageRepository;

    /**
     * @return UserRepository
     */
    public function getUserRepository(): UserRepository
    {
        return $this->userRepository;
    }

    /**
     * @param UserRepository $userRepository
     */
    public function setUserRepository(UserRepository $userRepository): void
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return MailRepository
     */
    public function getMailRepository(): MailRepository
    {
        return $this->mailRepository;
    }

    /**
     * @param MailRepository $mailRepository
     */
    public function setMailRepository(MailRepository $mailRepository): void
    {
        $this->mailRepository = $mailRepository;
    }

    /**
     * @return DiscordWebhookRepository
     */
    public function getDiscordWebhookRepository(): DiscordWebhookRepository
    {
        return $this->discordWebhookRepository;
    }

    /**
     * @param DiscordWebhookRepository $discordWebhookRepository
     */
    public function setDiscordWebhookRepository(DiscordWebhookRepository $discordWebhookRepository): void
    {
        $this->discordWebhookRepository = $discordWebhookRepository;
    }

    /**
     * @return DiscordMessageRepository
     */
    public function getDiscordMessageRepository(): DiscordMessageRepository
    {
        return $this->discordMessageRepository;
    }

    /**
     * @param DiscordMessageRepository $discordMessageRepository
     */
    public function setDiscordMessageRepository(DiscordMessageRepository $discordMessageRepository): void
    {
        $this->discordMessageRepository = $discordMessageRepository;
    }

}