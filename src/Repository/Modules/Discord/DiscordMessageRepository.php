<?php

namespace App\Repository\Modules\Discord;

use App\Entity\Modules\Discord\DiscordMessage;
use App\Entity\Modules\Discord\DiscordWebhook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DiscordMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscordMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscordMessage[]    findAll()
 * @method DiscordMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscordMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscordMessage::class);
    }

    /**
     * Will return all DiscordMessages
     *
     * @return DiscordMessage[]
     */
    public function getAllMessages(): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder->select("dm")
            ->from(DiscordMessage::class, "dm")
            ->orderBy("dm.created", Criteria::DESC);

        $query    = $queryBuilder->getQuery();
        $entities = $query->execute();

        return $entities;
    }

    /**
     * Will return the last processed DiscordMessages ordered by the creation date, reduced to the given count
     *
     * @param int $countOfReturnedMessages
     * @return DiscordMessage[]
     */
    public function getLastMessages(int $countOfReturnedMessages): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder->select("m")
            ->from(DiscordMessage::class, "m")
            ->orderBy("m.created", Criteria::DESC)
            ->setMaxResults($countOfReturnedMessages);

        $query   = $queryBuilder->getQuery();
        $results = $query->execute();

        return $results;
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
        $this->_em->persist($discordMessage);
        $this->_em->flush();

        return $discordMessage;
    }

    /**
     * Will return all the messages that can be processed further
     *
     * @return DiscordMessage[]
     */
    public function getAllProcessableMessages(): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder->select('dm')
            ->from(DiscordMessage::class, 'dm')
            ->join(DiscordWebhook::class, "dwh")
            ->where('dm.status IN (:statuses)')
            ->andWhere('dwh.webhookName != :webhookName')
            ->andWhere('dwh.deleted = 0')
            ->setParameter('statuses', DiscordMessage::PROCESSABLE_STATUSES, Connection::PARAM_STR_ARRAY)
            ->setParameter('webhookName', DiscordWebhook::PLACEHOLDER_WEBHOOK_NAME);

        $results = $queryBuilder->getQuery()->execute();

        return $results;
    }

}
