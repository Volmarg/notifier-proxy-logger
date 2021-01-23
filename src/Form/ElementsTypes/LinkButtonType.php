<?php

namespace App\Form\ElementsTypes;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Button which acts as a `<a>`
 *
 * Class LinkButtonType
 */
class LinkButtonType extends ButtonType
{
    const TYPE_OPTION_HREF      = "href";
    const TYPE_OPTION_REQUIRED  = "required";
    const TYPE_OPTION_LABEL     = "label";
    const TYPE_OPTION_TARGET    = "target";

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'link_button';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setRequired(self::TYPE_OPTION_HREF);

        $resolver->setDefault("mapped", false);
        $resolver->setDefault('auto_initialize', false);

        $resolver->setDefault(self::TYPE_OPTION_REQUIRED, false);
        $resolver->setDefault(self::TYPE_OPTION_TARGET, '_self');
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $view->vars[self::TYPE_OPTION_HREF]     = $options[self::TYPE_OPTION_HREF];
        $view->vars[self::TYPE_OPTION_LABEL]    = $options[self::TYPE_OPTION_LABEL];
        $view->vars[self::TYPE_OPTION_REQUIRED] = $options[self::TYPE_OPTION_REQUIRED];
        $view->vars[self::TYPE_OPTION_TARGET]   = $options[self::TYPE_OPTION_TARGET];
    }

}