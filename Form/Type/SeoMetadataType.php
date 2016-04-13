<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
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
     * @var string
     */
    private $dataClass;

    /**
     * @var bool
     */
    private $isOrm;

    /**
     * @param string $dataClass The FQCN of the data class to use for this form.
     * @param bool   $isOrm     Flag to know whether the form should be usable for doctrine ORM
     */
    public function __construct($dataClass, $isOrm = false)
    {
        $this->dataClass = $dataClass;
        $this->isOrm = $isOrm;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isSf28 = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix');
        $textType = $isSf28 ? 'Symfony\Component\Form\Extension\Core\Type\TextType' : 'text';
        $textareaType = $isSf28 ? 'Symfony\Component\Form\Extension\Core\Type\TextareaType' : 'textarea';
        $burgovKeyValueType = $isSf28 ? 'Burgov\Bundle\KeyValueFormBundle\Form\Type\KeyValueType' : 'burgov_key_value';

        $builder
            ->add('title', $textType, array('label' => 'form.label_title'))
            ->add('originalUrl', $textType, array('label' => 'form.label_originalUrl'))
            ->add('metaDescription', $textareaType, array('label' => 'form.label_metaDescription'))
            ->add('metaKeywords', $textareaType, array('label' => 'form.label_metaKeywords'))
            ->add('extraProperties', $burgovKeyValueType, array(
                'label' => 'form.label_extraProperties',
                'value_type' => 'text',
                'use_container_object' => true,
            ))
            ->add('extraNames', $burgovKeyValueType, array(
                'label' => 'form.label_extraNames',
                'value_type' => 'text',
                'use_container_object' => true,
            ))
            ->add('extraHttp', $burgovKeyValueType, array(
                'label' => 'form.label_extraHttp',
                'value_type' => 'text',
                'use_container_object' => true,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $defaults = array(
            'data_class' => $this->dataClass,
            'translation_domain' => 'CmfSeoBundle',
            'required' => false,
        );
        if ($this->isOrm) {
            $defaults['by_reference'] = false;
        }

        $resolver->setDefaults($defaults);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'seo_metadata';
    }
}
