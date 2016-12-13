<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Sitemap;

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\GuesserInterface;

/**
 * Test a guesser.
 */
abstract class GuesserTestCase extends \PHPUnit_Framework_Testcase
{
    /**
     * @var GuesserInterface
     */
    protected $guesser;

    /**
     * @var object
     */
    protected $data;

    public function setUp()
    {
        $this->guesser = $this->createGuesser();
        $this->data = $this->createData();
    }

    /**
     * Information is created.
     */
    public function testGuessCreate()
    {
        $urlInformation = new UrlInformation();

        $this->guesser->guessValues($urlInformation, $this->data, 'default');
        foreach ($this->getFields() as $field) {
            $this->assertNotNull($urlInformation->{'get'.$field}());
        }

        return $urlInformation;
    }

    /**
     * If the information is already set, it must not be overwritten.
     */
    public function testGuessNoOverwrite()
    {
        $urlInformation = new UrlInformation();
        foreach ($this->getFields() as $field) {
            $urlInformation->{'set'.$field}('always');
        }

        $this->guesser->guessValues($urlInformation, $this->data, 'default');
        foreach ($this->getFields() as $field) {
            $this->assertEquals('always', $urlInformation->{'get'.$field}());
        }
    }

    /**
     * Create the guesser for this test.
     *
     * @return GuesserInterface
     */
    abstract protected function createGuesser();

    /**
     * @return object
     */
    abstract protected function createData();

    /**
     * Provide list of fields in UrlInformation covered by this guesser.
     *
     * @return array
     */
    abstract protected function getFields();
}
