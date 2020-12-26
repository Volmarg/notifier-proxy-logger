<?php

namespace App\Repository\Modules\Mailing;

use App\Entity\Modules\Mailing\Mail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
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
        $entities = $this->findAll();
        return $entities;
    }

    /**
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
}
