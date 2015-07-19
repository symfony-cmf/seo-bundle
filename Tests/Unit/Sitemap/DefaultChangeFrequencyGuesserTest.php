<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Sitemap;

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\AbstractChain;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\DefaultChangeFrequencyGuesser;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\GuesserInterface;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\LocationGuesser;

class DefaultChangeFrequencyGuesserTest extends GuesserTestCase
{
    public function testGuessCreate()
    {
        $urlInformation = parent::testGuessCreate();
        $this->assertEquals('weekly', $urlInformation->getChangeFrequency());
    }

    /**
     * @inheritdoc
     */
    protected function createGuesser()
    {
        return new DefaultChangeFrequencyGuesser('weekly');
    }

    /**
     * @inheritdoc
     */
    protected function createData()
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function getFields()
    {
        return array('ChangeFrequency');
    }
}
