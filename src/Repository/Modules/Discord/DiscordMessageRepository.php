<?php

namespace App\Repository\Modules\Discord;

use App\Entity\Modules\Discord\DiscordMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
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

}
