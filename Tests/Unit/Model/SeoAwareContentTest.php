<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Model;

use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareContent;

class SeoAwareContentTest extends \PHPUnit_Framework_Testcase
{
    public function testRoute()
    {
        $content = new SeoAwareContent();
        $route = $this->getMockBuilder('Symfony\Component\Routing\Route')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $content->addRoute($route);
        $routes = $content->getRoutes();
        $this->assertCount(1, $routes);
        $routeArray = array();
        foreach ($routes as $route) {
            $routeArray[] = $route;
        }
        $this->assertEquals(array($route), $routeArray);

        $content->removeRoute($route);
        $this->assertCount(0, $content->getRoutes());
    }

    public function testLocale()
    {
        $content = new SeoAwareContent();
        $content->setLocale('fr');
        $this->assertEquals('fr', $content->getLocale());
    }
}
