<?php

namespace App\Controller;

use App\Controller\Core\ConfigLoaders;
use App\Controller\Core\Repositories;
use App\Entity\User;
use App\Services\Internal\LoggerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @deprecated
 */
class Application extends AbstractController
{
    /**
     * @var TranslatorInterface $translator
     */
    private TranslatorInterface $translator;

    /**
     * @var Forms $forms
     */
    private Forms $forms;

    /**
     * @var Repositories $repositories
     */
    private Repositories $repositories;

    /**
     * @var LoggerService $loggerService
     */
    private LoggerService $loggerService;

    /**
     * @var EntityManagerInterface $em
     */
    private EntityManagerInterface $em;

    /**
     * @var ConfigLoaders $configLoaders
     */
    private ConfigLoaders $configLoaders;

    /**
     * @return Forms
     */
    public function getForms(): Forms
    {
        return $this->forms;
    }

    /**
     * @param Forms $forms
     */
    public function setForms(Forms $forms): void
    {
        $this->forms = $forms;
    }

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }

    /**
     * Will translate a string using data in `/translations/`
     *
     * @param string $string
     * @param array $parameters
     * @return string
     */
    public function trans(string $string, array $parameters = []): string
    {
        return $this->translator->trans($string, $parameters);
    }

    /**
     * @return Repositories
     */
    public function getRepositories(): Repositories
    {
        return $this->repositories;
    }

    /**
     * @param Repositories $repositories
     */
    public function setRepositories(Repositories $repositories): void
    {
        $this->repositories = $repositories;
    }

    /**
     * Will return logged in user - not just the UserInterface but the actual entity from database with all its data
     */
    public function getLoggedInUser(): User
    {
        $loggedInBaseUserInterface = $this->getUser();
        $loggedInBaseUserInterface->getUsername();

        $userEntity = $this->getRepositories()->getUserRepository()->findOneByName($loggedInBaseUserInterface->getUsername());

        return $userEntity;
    }

    /**
     * @return LoggerService
     */
    public function getLoggerService(): LoggerService
    {
        return $this->loggerService;
    }

    /**
     * @param LoggerService $loggerService
     */
    public function setLoggerService(LoggerService $loggerService): void
    {
        $this->loggerService = $loggerService;
    }

    /**
     * @param EntityManagerInterface $em
     */
    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    /**
     * @return ConfigLoaders
     */
    public function getConfigLoaders(): ConfigLoaders
    {
        return $this->configLoaders;
    }

    /**
     * @param ConfigLoaders $configLoaders
     */
    public function setConfigLoaders(ConfigLoaders $configLoaders): void
    {
        $this->configLoaders = $configLoaders;
    }

    public function beginTransaction()
    {
        $this->em->beginTransaction();
    }

    public function commitTransaction()
    {
        $this->em->commit();
    }

    public function rollbackTransaction()
    {
        $this->em->rollback();
    }
}