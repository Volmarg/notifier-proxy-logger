<?php

namespace App\Controller;

use App\Controller\Core\Repositories;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @return string
     */
    public function trans(string $string): string
    {
        return $this->translator->trans($string);
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
}