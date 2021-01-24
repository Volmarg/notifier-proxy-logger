<?php

namespace App\Repository\Modules\Mailing;

use App\Entity\Modules\Mailing\Mail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mail[]    findAll()
 * @method Mail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mail::class);
    }

    /**
     * Will return all Emails
     *
     * @return array
     */
    public function getAllEmails(): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder->select("m")
            ->from(Mail::class, "m")
            ->orderBy("m.created", Criteria::DESC);

        $query    = $queryBuilder->getQuery();
        $entities = $query->execute();

        return $entities;
    }

    /**
     * Will return the last processed Emails ordered by the creation date, reduced to the given count
     *
     * @param int $countOfReturnedEmails
     * @return Mail[]
     */
    public function getLastEmails(int $countOfReturnedEmails): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder->select("m")
            ->from(Mail::class, "m")
            ->orderBy("m.created", Criteria::DESC)
            ->setMaxResults($countOfReturnedEmails);

        $query   = $queryBuilder->getQuery();
        $results = $query->execute();

        return $results;
    }

    /**
     * Will save the entity state, creates new DB entry if no such entity exists or updates state of the existing one
     *
     * @param Mail $mail
     * @return Mail
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveEntity(Mail $mail): Mail
    {
        $this->_em->persist($mail);
        $this->_em->flush();

        return $mail;
    }

    /**
     * Will return all the emails that can be processed further
     *
     * @return Mail[]
     */
    public function getAllProcessableEmails(): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder->select('m')
            ->from(Mail::class, 'm')
            ->where('m.status IN (:statuses)')
            ->setParameter('statuses', Mail::PROCESSABLE_STATUSES, Connection::PARAM_STR_ARRAY);

        $results = $queryBuilder->getQuery()->execute();

        return $results;
    }

}
