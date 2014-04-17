<?php


namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Model;


use Symfony\Cmf\Bundle\SeoBundle\Model\ExtraProperty;

class ExtraPropertyTest extends \PHPUnit_Framework_TestCase {

    /**
     * @expectedException Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException
     */
    public function testExceptionForNotAllowedType()
    {
        new ExtraProperty('test', 'test', 'test');
    }
}
 