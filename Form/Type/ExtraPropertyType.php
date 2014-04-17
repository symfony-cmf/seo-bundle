<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\Form\Type;

use Symfony\Cmf\Bundle\SeoBundle\Model\ExtraProperty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form type for the extra properties.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
class ExtraPropertyType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'seo_extra_property';
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('key', 'text')
            ->add('value', 'text')
        ;

        // add a select field depending on the allowed types
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $form = $event->getForm();
            $choice = array();
            foreach (ExtraProperty::getAllowedTypes() as $type) {
                $choice[] = array('label' => $type, 'value' => $type);
            }

            $form->add('type', 'collection', array('options' => array('choices' => $choice)));
        });
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'label'        => false,
            'allow_add'    => true,
            'allow_delete' => true,
            'required'     => false,
        ));
    }
}
