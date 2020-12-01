<?php

namespace App\Form\User;

use App\Controller\Application;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegisterForm extends AbstractType
{
    const FIELD_NAME_USERNAME = "username";
    const FIELD_NAME_PASSWORD = "password";
    const FIELD_NAME_SUBMIT   = "submit";

    /**
     * @var Application $application
     */
    private Application $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_NAME_USERNAME, TextType::class, [
                "attr" => [
                    "placeholder" => $this->application->trans('forms.registerForm.username.placeholder'),
                ],
                "label" => $this->application->trans('forms.registerForm.username.label'),
            ])
            ->add(self::FIELD_NAME_PASSWORD, PasswordType::class, [
                "attr" => [
                    "placeholder" => $this->application->trans('forms.registerForm.password.placeholder'),
                ],
                "label" => $this->application->trans('forms.registerForm.password.label'),
            ])
            ->add(self::FIELD_NAME_SUBMIT, SubmitType::class, [
                "label" => $this->application->trans('forms.registerForm.submit.label'),
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
