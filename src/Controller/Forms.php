<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\User\UserLoginForm;
use App\Form\User\UserRegisterForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

class Forms extends AbstractController
{
    /**
     * @param User|null $user
     * @param array $options
     * @return FormInterface
     */
    public function getUserLoginForm(?User $user = null, array $options = []): FormInterface
    {
        return $this->createForm(UserLoginForm::class, null, $options);
    }

    /**
     * @param User|null $user
     * @param array $options
     * @return FormInterface
     */
    public function getUserRegisterForm(?User $user = null, array $options = []): FormInterface
    {
        return $this->createForm(UserRegisterForm::class, null, $options);
    }
}