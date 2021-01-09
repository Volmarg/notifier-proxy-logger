<?php

namespace App\Form\Modules\Discord;

use App\Controller\Application;
use App\Controller\Core\Controllers;
use App\Entity\Modules\Discord\DiscordWebhook;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendTestDiscordMessageForm extends AbstractType
{

    const FORM_DATA_WEBHOOKS_ENTITIES_ARRAY = 'webhooksEntitiesArray';

    const FIELD_NAME_WEBHOOKS = "webhooks";
    const FIELD_NAME_MESSAGE  = "message";
    const FIELD_NAME_SUBMIT   = "submit";

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
        $webhooks = $options[self::FORM_DATA_WEBHOOKS_ENTITIES_ARRAY];

        $builder
            ->add(self::FIELD_NAME_WEBHOOKS, EntityType::class, [
                "choices"      => $webhooks,
                "class"        => DiscordWebhook::class,
                "choice_label" => DiscordWebhook::FIELD_NAME_WEBHOOK_NAME,
            ])
            ->add(self::FIELD_NAME_MESSAGE, TextareaType::class, [
            ])
            ->add(self::FIELD_NAME_SUBMIT, SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(self::FORM_DATA_WEBHOOKS_ENTITIES_ARRAY);
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);
    }

}
