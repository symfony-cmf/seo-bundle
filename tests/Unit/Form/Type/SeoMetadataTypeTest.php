<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Burgov\Bundle\KeyValueFormBundle\Form\Type\KeyValueRowType;
use Burgov\Bundle\KeyValueFormBundle\Form\Type\KeyValueType;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Form\Type\SeoMetadataType;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata as SeoMetadataModel;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Tests\Extension\Validator\Type\TypeTestCase;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class SeoMetadataTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        return array_merge(
            parent::getExtensions(),
            [new PreloadedExtension([
                new KeyValueType(),
                new KeyValueRowType(),
                new SeoMetadataType('Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoMetadata'),
                new SeoMetadataTypeTest_OrmType('Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata'),
            ], [])]
        );
    }

    public function testDataClassCreationForPhpcr()
    {
        $this->validator->expects($this->any())->method('validate')->will($this->returnValue([]));

        $formData = [
            'title' => 'Title',
            'originalUrl' => 'original/url',
            'extraProperties' => [],
            'extraNames' => [],
            'extraHttp' => [],
        ];

        $form = $this->factory->create(SeoMetadataType::class);
        $this->assertTrue($form->getConfig()->getByReference());

        $object = new SeoMetadata();
        $object->setTitle($formData['title']);
        $object->setOriginalUrl($formData['originalUrl']);
        $object->setExtraNames($formData['extraNames']);
        $object->setExtraHttp($formData['extraHttp']);
        $object->setExtraProperties($formData['extraProperties']);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());
    }

    public function testDataClassCreationForNonPhpcr()
    {
        $this->validator->expects($this->any())->method('validate')->will($this->returnValue([]));

        $formData = [
            'title' => 'Title',
            'originalUrl' => 'original/url',
            'extraProperties' => [],
            'extraNames' => [],
            'extraHttp' => [],
        ];

        $form = $this->factory->create(SeoMetadataTypeTest_OrmType::class);
        $this->assertFalse($form->getConfig()->getByReference());

        $object = new SeoMetadataModel();
        $object->setTitle($formData['title']);
        $object->setOriginalUrl($formData['originalUrl']);
        $object->setExtraNames($formData['extraNames']);
        $object->setExtraHttp($formData['extraHttp']);
        $object->setExtraProperties($formData['extraProperties']);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());
    }
}

class SeoMetadataTypeTest_OrmType extends SeoMetadataType
{
    public function __construct($dataClass)
    {
        parent::__construct($dataClass, ['storage' => 'orm']);
    }
}
