<?php

namespace App\Repository\Modules\Mailing;

use App\Entity\Modules\Mailing\MailAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MailAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method MailAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method MailAccount[]    findAll()
 * @method MailAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailAccount::class);
    }

    /**
     * Will create new entity if such one does not exist or update the existing one
     *
     * @param MailAccount $mailAccount
     * @return MailAccount
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveMailAccount(MailAccount $mailAccount): MailAccount
    {
        $this->_em->persist($mailAccount);
        $this->_em->flush();

        return $mailAccount;
    }

    /**
     * Wil return the default mail account
     *
     * @return MailAccount
     */
    public function getDefaultMailAccount(): MailAccount
    {
        return $this->findOneBy([
            MailAccount::FIELD_NAME => MailAccount::DEFAULT_CONNECTION_NAME,
        ]);
    }

    /**
     * Will return all mail accounts
     *
     * @return MailAccount[]
     */
    public function getAllMailAccounts(): array
    {
        return $this->findAll();
    }

    /**
     * Will return one mail account or null if none for given id was found
     *
     * @param string $id
     * @return MailAccount|null
     */
    public function getOneById(string $id): ?MailAccount
    {
        return $this->find($id);
    }

    /**
     * Will hard delete the entity
     *
     * @param MailAccount $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function hardDelete(MailAccount $entity): void
    {
        $this->_em->remove($entity);
        $this->_em->flush();;
    }

}
