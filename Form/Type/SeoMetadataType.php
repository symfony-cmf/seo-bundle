<?php

namespace Cmf\SeoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SeoMetadataType extends AbstractType
{

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text')
                ->add(
                    'titleStrategy',
                    'choice',
                    array(
                    'choices' => array(
                        'prepend'   => 'form.label.title.prepend',
                        'append'    => 'form.label.title.append',
                        'replace'   => 'form.label.title.replace'
                    )
                    )
                )
                ->add('originalUrl', 'text')
                ->add(
                    'originalUrlStrategy',
                    'choice',
                    array(
                            'choices' =>
                            array(
                            'canonical' => 'form.label.canonical.link',
                            'redirect' => 'form.label.redirect.link'
                        )
                    )
                )
                ->add('metaDescription', 'textarea')
                ->add('metaKeywords', 'textarea');
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Cmf\SeoBundle\Model\SeoMetadata',
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'seo_metadata';
    }
}
