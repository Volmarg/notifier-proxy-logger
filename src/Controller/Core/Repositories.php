<?php


namespace App\Controller\Core;


use App\Repository\Modules\Mailing\MailRepository;
use App\Repository\UserRepository;

class Repositories
{
    /**
     * @var UserRepository $userRepository
     */
    private UserRepository $userRepository;

    /**
     * @var MailRepository $mailRepository
     */
    private MailRepository $mailRepository;

    /**
     * @return UserRepository
     */
    public function getUserRepository(): UserRepository
    {
        return $this->userRepository;
    }

    /**
     * @param UserRepository $userRepository
     */
    public function setUserRepository(UserRepository $userRepository): void
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return MailRepository
     */
    public function getMailRepository(): MailRepository
    {
        return $this->mailRepository;
    }

    /**
     * @param MailRepository $mailRepository
     */
    public function setMailRepository(MailRepository $mailRepository): void
    {
        $this->mailRepository = $mailRepository;
    }


}