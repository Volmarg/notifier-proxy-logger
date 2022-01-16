<?php

namespace App\Controller\Modules\Mailing;

use App\Controller\Application;
use App\Entity\Modules\Mailing\MailAccount;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handles general data related to mail accounts
 */
class MailAccountController extends AbstractController
{
    /**
     * @var Application $app
     */
    private Application $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
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
        return $this->app->getRepositories()->getMailAccountRepository()->saveMailAccount($mailAccount);
    }

    /**
     * Wil return the default mail account
     *
     * @return MailAccount
     * @throws Exception
     */
    public function getDefaultMailAccount(): MailAccount
    {
        $mailAccount = $this->app->getRepositories()->getMailAccountRepository()->getDefaultMailAccount();
        if( empty($mailAccount) ){
            throw new NotFoundHttpException("Default mail account was not found in the database!");
        }

        return $mailAccount;
    }

    /**
     * Will return all mail accounts
     *
     * @return MailAccount[]
     */
    public function getAllMailAccounts(): array
    {
        return $this->app->getRepositories()->getMailAccountRepository()->getAllMailAccounts();
    }

    /**
     * Will return one mail account or null if none for given id was found
     *
     * @param string $id
     * @return MailAccount|null
     */
    public function getOneById(string $id): ?MailAccount
    {
        return $this->app->getRepositories()->getMailAccountRepository()->getOneById($id);
    }

    /**
     * Will hard delete the entity, and assign them to placeholder webhook
     *
     * @param MailAccount $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function hardDelete(MailAccount $entity): void
    {
        $this->app->getRepositories()->getMailAccountRepository()->hardDelete($entity);
    }

    /**
     * Will save entity if it's a new one, or update already existing
     *
     * @param MailAccount $mailAccount
     * @return MailAccount
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(MailAccount $mailAccount): MailAccount
    {
        return $this->app->getRepositories()->getMailAccountRepository()->save($mailAccount);
    }

}