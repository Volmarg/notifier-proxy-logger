<?php

namespace App\Controller\Modules\Discord;

use App\Controller\Application;
use App\DTO\Modules\Discord\DiscordMessageDTO;
use App\Entity\Modules\Discord\DiscordMessage;
use App\Exception\NoEntityWasFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DiscordMessageController extends AbstractController
{

    /**
     * @var Application $app
     */
    private Application $app;

    /**
     * @var DiscordWebhookController $discordWebhookController
     */
    private DiscordWebhookController $discordWebhookController;

    public function __construct(Application $app, DiscordWebhookController $discordWebhookController)
    {
        $this->app                      = $app;
        $this->discordWebhookController = $discordWebhookController;
    }

    /**
     * Will return all Discord Messages
     *
     * @return DiscordMessage[]
     */
    public function getAllMessages(): array
    {
        return $this->app->getRepositories()->getDiscordMessageRepository()->getAllMessages();
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

    /**
     * Will build DiscordMessage entity from DiscordMessageDTO
     *
     * @param DiscordMessageDTO $discordMessageDto
     * @return DiscordMessage
     * @throws NoEntityWasFoundException
     */
    public function buildDiscordMessageEntityFromDto(DiscordMessageDTO $discordMessageDto): DiscordMessage
    {
        $messageTitle   = trim($discordMessageDto->getMessageTitle());
        $messageContent = trim($discordMessageDto->getMessageContent());

        $discordMessage = new DiscordMessage();
        $discordMessage->setMessageContent($messageContent);
        $discordMessage->setMessageTitle($messageTitle);
        $discordMessage->setSource($discordMessageDto->getSource());

        $discordWebhook = $this->discordWebhookController->getOneByWebhookName($discordMessageDto->getWebhookName());
        if( empty($discordWebhook) ){
            throw new NoEntityWasFoundException("No DiscordWebhook entity was found for given webhook name: {$discordMessageDto->getWebhookName()}");
        }

        $discordMessage->setDiscordWebhook($discordWebhook);
        return $discordMessage;
    }

    /**
     * Will return all the messages that can be processed further
     *
     * @return DiscordMessage[]
     */
    public function getAllProcessableMessages(): array
    {
        return $this->app->getRepositories()->getDiscordMessageRepository()->getAllProcessableMessages();
    }

    /**
     * Will set given status to the entity and will update it the database
     *
     * @param DiscordMessage $message
     * @param string $status
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateStatus(DiscordMessage $message, string $status): void
    {
        $message->setStatus($status);
        $this->saveEntity($message);
    }

}