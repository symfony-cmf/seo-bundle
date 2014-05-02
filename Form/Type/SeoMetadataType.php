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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * A form type for editing the SEO metadata.
 *
 * When using SonataAdmin for the backend and having content that implement
 * the SeoAwareInterface. The Sonata Admin will get this form type automatically.
 *
 * You can explicitly use this type using the "seo_metadata" type.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoMetadataType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $keyValueOptions = array(
            'required' => false,
            'value_type' => 'text',
            'use_container_object' => true,
        );

        $builder
            ->add('title', 'text', array('required' => false))
            ->add('originalUrl', 'text', array('required' => false))
            ->add('metaDescription', 'textarea', array('required' => false))
            ->add('metaKeywords', 'textarea', array('required' => false))
            ->add('extraProperties', 'burgov_key_value', $keyValueOptions)
            ->add('extraNames', 'burgov_key_value', $keyValueOptions)
            ->add('extraHttp', 'burgov_key_value', $keyValueOptions)
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'seo_metadata';
    }
}
