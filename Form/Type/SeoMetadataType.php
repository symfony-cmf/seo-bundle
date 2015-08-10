<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * A form type for editing the SEO metadata.
 *
 * When using SonataAdmin for the backend and having content that implements
 * the SeoAwareInterface. The Sonata Admin will get this form type automatically.
 *
 * You can explicitly use this type using the "seo_metadata" type.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoMetadataType extends AbstractType
{
    /**
     * @var
     */
    private $dataClass;

    /**
     * @param string $dataClass The FQCN of the data class to use for this form.
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label' => 'form.label_title'))
            ->add('originalUrl', 'text', array('label'=> 'form.label_originalUrl'))
            ->add('metaDescription', 'textarea', array('label' => 'form.label_metaDescription'))
            ->add('metaKeywords', 'textarea', array('label' => 'form.label_metaKeywords'))
            ->add('extraProperties', 'burgov_key_value', array(
                'label' => 'form.label_extraProperties',
                'value_type' => 'text',
                'use_container_object' => true,
            ))
            ->add('extraNames', 'burgov_key_value', array(
                'label' => 'form.label_extraNames',
                'value_type' => 'text',
                'use_container_object' => true,
            ))
            ->add('extraHttp', 'burgov_key_value', array(
                'label' => 'form.label_extraHttp',
                'value_type' => 'text',
                'use_container_object' => true,
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass,
            'translation_domain' => 'CmfSeoBundle',
            'required' => false,
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
