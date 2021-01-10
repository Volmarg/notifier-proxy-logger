<?php

namespace App\Controller\Modules\Discord;

use App\Controller\Application;
use App\Entity\Modules\Discord\DiscordMessage;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DiscordMessageController extends AbstractController
{

    /**
     * @var Application $app
     */
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Will return all Discord Messages
     *
     * @return DiscordMessage[]
     */
    public function getAllMessages(): array
    {
        return $this->app->getRepositories()->getMailRepository()->getAllEmails();
    }

    /**
     * Will return the last processed Discord Messages ordered by the creation date, reduced to the given count
     *
     * @param int $countOfReturnedMessages
     * @return DiscordMessage[]
     */
    public function getLastMessages(int $countOfReturnedMessages): array
    {
        return $this->app->getRepositories()->getDiscordMessageRepository()->getLastMessages($countOfReturnedMessages);
    }

    /**
     * Will save the entity state, creates new DB entry if no such entity exists or updates state of the existing one
     *
     * @param DiscordMessage $discordMessage
     * @return DiscordMessage
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveEntity(DiscordMessage $discordMessage): DiscordMessage
    {
        return $this->app->getRepositories()->getDiscordMessageRepository()->saveEntity($discordMessage);
    }

}