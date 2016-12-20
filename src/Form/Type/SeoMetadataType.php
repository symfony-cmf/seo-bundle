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

use Burgov\Bundle\KeyValueFormBundle\Form\Type\KeyValueType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
     * @param string $dataClass the FQCN of the data class to use for this form
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
        $builder
            ->add('title', TextType::class, array('label' => 'form.label_title'))
            ->add('originalUrl', TextType::class, array('label' => 'form.label_originalUrl'))
            ->add('metaDescription', TextareaType::class, array('label' => 'form.label_metaDescription'))
            ->add('metaKeywords', TextareaType::class, array('label' => 'form.label_metaKeywords'))
            ->add('extraProperties', KeyValueType::class, array(
                'label' => 'form.label_extraProperties',
                'value_type' => TextType::class,
                'use_container_object' => true,
            ))
            ->add('extraNames', KeyValueType::class, array(
                'label' => 'form.label_extraNames',
                'value_type' => TextType::class,
                'use_container_object' => true,
            ))
            ->add('extraHttp', KeyValueType::class, array(
                'label' => 'form.label_extraHttp',
                'value_type' => TextType::class,
                'use_container_object' => true,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass,
            'translation_domain' => 'CmfSeoBundle',
            'required' => false,
        ));

        if ($this->isOrm) {
            $resolver->setDefault('by_reference', false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'seo_metadata';
    }
}
