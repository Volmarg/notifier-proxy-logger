<?php

namespace App\Controller\Modules\Discord;

use App\Controller\Application;
use App\Entity\Modules\Discord\DiscordWebhook;
use App\Entity\SoftDeletableInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use TypeError;

class DiscordWebhookController extends AbstractController
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
     * Will return one discord webhook for provided hook name, if no such hook exists - null is returned
     *
     * @param string $webhookName
     * @return DiscordWebhook|null
     */
    public function getOneByWebhookName(string $webhookName): ?DiscordWebhook
    {
        return $this->app->getRepositories()->getDiscordWebhookRepository()->getOneByWebhookName($webhookName);
    }

    /**
     * Will return one webhook or null if no webhook for given id was found
     *
     * @param string $id
     * @return DiscordWebhook|null
     */
    public function getOneById(string $id): ?DiscordWebhook
    {
        return $this->app->getRepositories()->getDiscordWebhookRepository()->getOneById($id);
    }

    /**
     * Will return all entities
     *
     * @return DiscordWebhook[]
     */
    public function getAll(): array
    {
        return $this->app->getRepositories()->getDiscordWebhookRepository()->findBy([
            SoftDeletableInterface::FIELD_NAME_DELETED => false,
        ]);
    }

    /**
     * Will save entity if it's a new one, or update already existing
     *
     * @param DiscordWebhook $discordWebhook
     * @return DiscordWebhook
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(DiscordWebhook $discordWebhook): DiscordWebhook
    {
        return $this->app->getRepositories()->getDiscordWebhookRepository()->save($discordWebhook);
    }

    /**
     * Will soft delete the entity
     *
     * @param DiscordWebhook $discordWebhook
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function softDelete(DiscordWebhook $discordWebhook): void
    {
        $discordWebhook->setDeleted(true);
        $this->app->getRepositories()->getDiscordWebhookRepository()->save($discordWebhook);
    }

    /**
     * Will hard delete the entity, and assign them to placeholder webhook
     *
     * @param DiscordWebhook $discordWebhook
     */
    public function hardDelete(DiscordWebhook $discordWebhook): void
    {
        $this->app->beginTransaction();
        {
            try{
                $placeholderWebhook = $this->getPlaceholderWebhook();
                foreach($discordWebhook->getDiscordMessages() as $discordMessage){
                    $discordMessage->setDiscordWebhook($placeholderWebhook);
                    $this->app->getRepositories()->getDiscordMessageRepository()->saveEntity($discordMessage);
                }

                $this->app->getRepositories()->getDiscordWebhookRepository()->hardDelete($discordWebhook);
            }catch(Exception|TypeError $e){
                $this->app->rollbackTransaction();
                $this->app->getLoggerService()->logThrowable($e);
                throw $e;
            }
        }
        $this->app->commitTransaction();

    }

    /**
     * Will return placeholder webhook used to keep the messages despite the fact that parent webhooks are removed
     * @throws Exception
     */
    public function getPlaceholderWebhook(): ?DiscordWebhook
    {
        $webhook = $this->app->getRepositories()->getDiscordWebhookRepository()->getPlaceholderWebhook();

        if( empty($webhook) ){
            $message = "Placeholder DiscordWebhook was not found!";
            $this->app->getLoggerService()->getLogger()->emergency($message);
            throw new Exception($message);
        }

        return $webhook;
    }
}