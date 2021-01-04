<?php

namespace App\Controller\Modules\Discord;

use App\Controller\Application;
use App\Entity\Modules\Discord\DiscordWebhook;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * Will return all entities
     *
     * @return DiscordWebhook[]
     */
    public function getAll(): array
    {
        return $this->app->getRepositories()->getDiscordWebhookRepository()->findAll();
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

}