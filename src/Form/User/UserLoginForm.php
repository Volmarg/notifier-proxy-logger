<?php

namespace App\Form\User;

use App\Controller\Application;
use App\Entity\User;
use App\Form\ElementsTypes\LinkButtonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserLoginForm extends AbstractType
{
    const FIELD_NAME_USERNAME = "username";
    const FIELD_NAME_PASSWORD = "password";
    const FIELD_NAME_SUBMIT   = "submit";
    const FIELD_NAME_REGISTER = "register";

    /**
     * @var Application $application
     */
    private Application $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_NAME_USERNAME, TextType::class, [
                "attr" => [
                    "placeholder" => $this->application->trans('forms.loginForm.username.placeholder'),
                ],
                "label" => $this->application->trans('forms.loginForm.username.label'),
            ])
            ->add(self::FIELD_NAME_PASSWORD, PasswordType::class, [
                "attr" => [
                    "placeholder" => $this->application->trans('forms.loginForm.password.placeholder'),
                ],
                "label" => $this->application->trans('forms.loginForm.password.label'),
            ])
            ->add(self::FIELD_NAME_SUBMIT, SubmitType::class, [
                "label" => $this->application->trans('forms.loginForm.submit.label'),
            ])
            ->add(self::FIELD_NAME_REGISTER, LinkButtonType::class, [
                "label" => $this->application->trans('forms.loginForm.register.label'),
                "href"  => "http://www.google.com/",
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => User::class,
            'allow_extra_fields' => true,
        ]);
    }
}
