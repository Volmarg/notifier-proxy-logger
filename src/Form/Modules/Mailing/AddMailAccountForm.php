<?php

namespace App\Form\Modules\Mailing;

use App\Controller\Application;
use App\Controller\Core\Controllers;
use App\Entity\Modules\Mailing\MailAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddMailAccountForm extends AbstractType
{

    const FIELD_NAME_CLIENT   = 'client';
    const FIELD_NAME_NAME     = 'name';
    const FIELD_NAME_LOGIN    = 'login';
    const FIELD_NAME_PASSWORD = 'password';
    const FIELD_NAME_SUBMIT   = 'submit';

    /**
     * @var Application $application
     */
    private Application $application;

    /**
     * @var Controllers $controllers
     */
    private Controllers $controllers;

    public function __construct(Application $application, Controllers $controllers)
    {
        $this->application = $application;
        $this->controllers = $controllers;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_NAME_CLIENT, TextType::class)
            ->add(self::FIELD_NAME_NAME, TextType::class)
            ->add(self::FIELD_NAME_LOGIN, TextType::class)
            ->add(self::FIELD_NAME_PASSWORD, PasswordType::class)
            ->add(self::FIELD_NAME_SUBMIT, SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MailAccount::class,
        ]);
    }

}
