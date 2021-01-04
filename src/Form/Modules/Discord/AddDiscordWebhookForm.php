<?php

namespace App\Form\Modules\Discord;

use App\Controller\Application;
use App\Controller\Core\Controllers;
use App\Entity\Modules\Discord\DiscordWebhook;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddDiscordWebhookForm extends AbstractType
{

    const FIELD_NAME_USERNAME     = 'username';
    const FIELD_NAME_WEBHOOK_URL  = 'webhookUrl';
    const FIELD_NAME_DESCRIPTION  = 'description';
    const FIELD_NAME_WEBHOOK_NAME = 'webhookName';
    const FIELD_NAME_SUBMIT       = 'submit';

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
            ->add(self::FIELD_NAME_WEBHOOK_NAME, TextType::class)
            ->add(self::FIELD_NAME_WEBHOOK_URL, TextType::class)
            ->add(self::FIELD_NAME_USERNAME, TextType::class)
            ->add(self::FIELD_NAME_DESCRIPTION, TextareaType::class)
            ->add(self::FIELD_NAME_SUBMIT, SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DiscordWebhook::class,
        ]);
    }

}
