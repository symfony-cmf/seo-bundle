<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\EventListener;

use Symfony\Cmf\Bundle\SeoBundle\EventListener\SeoContentListener;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SeoContentListenerTest extends BaseTestCase
{
    public function testRedirectRoute()
    {
        $seoPresentation = $this->getMockBuilder('Symfony\Cmf\Bundle\SeoBundle\Model\SeoPresentation')
                                      ->disableOriginalConstructor()
                                      ->getMock();

        $redirectResponse = new RedirectResponse('/test');
        $seoPresentation->expects($this->once())
                        ->method('getRedirectResponse')
                        ->will($this->returnValue($redirectResponse));

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                        ->disableOriginalConstructor()
                        ->getMock();

        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
                      ->disableOriginalConstructor()
                      ->getMock();
        $event->expects($this->any())
              ->method('getRequest')
              ->will($this->returnValue($request));
        $event->expects($this->once())
              ->method('setResponse')
              ->with($redirectResponse);

        $attributes = $this->getMockBuilder('\Symfony\Component\HttpFoundation\ParameterBag')
                           ->disableOriginalConstructor()
                           ->getMock();
        $attributes->expects($this->once())->method('has')->will($this->returnValue(true));
        $request->attributes = $attributes;

        $seoListener = new SeoContentListener($seoPresentation);
        $seoListener->onKernelRequest($event);
    }
}
