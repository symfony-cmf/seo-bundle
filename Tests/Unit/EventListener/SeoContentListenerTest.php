<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Cmf\Bundle\SeoBundle\EventListener\SeoContentListener;
use Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter;

class SeoContentListenerTest extends \PHPUnit_Framework_Testcase
{
    public function testRedirectRoute()
    {
        $seoPresentation = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\Model\SeoPresentationInterface');

        $redirectResponse = new RedirectResponse('/test');
        $seoPresentation
            ->expects($this->once())
            ->method('updateSeoPage')
            ->with($this) // the content can be anything. use the test instance to not create another mock.
        ;
        $seoPresentation
            ->expects($this->once())
            ->method('getRedirectResponse')
            ->will($this->returnValue($redirectResponse))
        ;

        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');

        $event = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $event
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request))
        ;
        $event
            ->expects($this->once())
            ->method('setResponse')
            ->with($redirectResponse)
        ;

        $attributes = $this->getMock('Symfony\Component\HttpFoundation\ParameterBag');
        $attributes
            ->expects($this->once())
            ->method('has')
            ->with(DynamicRouter::CONTENT_KEY)
            ->will($this->returnValue(true))
        ;
        $attributes
            ->expects($this->once())
            ->method('get')
            ->with(DynamicRouter::CONTENT_KEY)
            ->will($this->returnValue($this))
        ;
        $request->attributes = $attributes;

        $seoListener = new SeoContentListener($seoPresentation, DynamicRouter::CONTENT_KEY);
        $seoListener->onKernelRequest($event);
    }
}
