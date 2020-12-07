<?php

namespace App\Form\Modules\Mailing;

use App\Controller\Application;
use App\DTO\Modules\Mailing\SendTestMailDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendTestMailForm extends AbstractType
{
    const FIELD_NAME_RECEIVER      = "receiver";
    const FIELD_NAME_MESSAGE_BODY  = "messageBody";
    const FIELD_NAME_MESSAGE_TITLE = "messageTitle";
    const FIELD_NAME_SUBMIT        = "submit";

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
            ->add(self::FIELD_NAME_RECEIVER, TextType::class, [
                "attr" => [
                    "placeholder" => $this->application->trans('forms.sendTestMailForm.receiver.placeholder'),
                ],
                "label" => $this->application->trans('forms.sendTestMailForm.receiver.label'),
            ])
            ->add(self::FIELD_NAME_MESSAGE_TITLE, TextType::class, [
                "attr" => [
                    "placeholder" => $this->application->trans('forms.sendTestMailForm.messageTitle.placeholder'),
                ],
                "label" => $this->application->trans('forms.sendTestMailForm.messageTitle.label'),
            ])
            ->add(self::FIELD_NAME_MESSAGE_BODY, TextareaType::class, [
                "attr" => [
                    "placeholder" => $this->application->trans('forms.sendTestMailForm.messageTitle.placeholder'),
                ],
                "label" => $this->application->trans('forms.sendTestMailForm.messageTitle.label'),
            ])
            ->add(self::FIELD_NAME_SUBMIT, SubmitType::class, [
                "label" => $this->application->trans('forms.sendTestMailForm.submit'),
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SendTestMailDTO::class,
        ]);
    }
}
