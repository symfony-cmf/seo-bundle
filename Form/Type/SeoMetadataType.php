<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * A form type for editing the SEO metadata.
 *
 * When using SonataAdmin for the backend and having documents that implement the SeoAwareInterface. The 
 * Sonata Admin will get this form type automatically.
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
        $builder
            ->add('title', 'text')
            ->add('originalUrl', 'text')
            ->add('metaDescription', 'textarea')
            ->add('metaKeywords', 'textarea')
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
