<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
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
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoMetadataType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    /**
     * @var array
     */
    private $options;

    /**
     * @param string $dataClass the FQCN of the data class to use for this form
     * @param array  $options   List of options to tweak the form
     *                          - string  "storage"  Storage system that is used to use the correct form settings. undefined|phpcr|orm
     *                          - boolean "generic_metadata" Whether to enable extra fields. Requires BurgovKeyValueFormBundle
     */
    public function __construct($dataClass, array $options = [])
    {
        $this->dataClass = $dataClass;
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'storage' => 'undefined',
            'generic_metadata' => false,
        ]);
        $resolver->setAllowedValues('storage', ['undefined', 'phpcr', 'orm']);
        $resolver->setAllowedTypes('generic_metadata', 'boolean');
        $this->options = $resolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'form.label_title'])
            ->add('originalUrl', TextType::class, ['label' => 'form.label_originalUrl'])
            ->add('metaDescription', TextareaType::class, ['label' => 'form.label_metaDescription'])
            ->add('metaKeywords', TextareaType::class, ['label' => 'form.label_metaKeywords'])
        ;
        if ($this->options['generic_metadata']) {
            $builder
                ->add('extraProperties', KeyValueType::class, [
                    'label' => 'form.label_extraProperties',
                    'value_type' => TextType::class,
                    'use_container_object' => true,
                ])
                ->add('extraNames', KeyValueType::class, [
                    'label' => 'form.label_extraNames',
                    'value_type' => TextType::class,
                    'use_container_object' => true,
                ])
                ->add('extraHttp', KeyValueType::class, [
                    'label' => 'form.label_extraHttp',
                    'value_type' => TextType::class,
                    'use_container_object' => true,
                ])
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'translation_domain' => 'CmfSeoBundle',
            'required' => false,
        ]);

        if ('orm' === $this->options['storage']) {
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
