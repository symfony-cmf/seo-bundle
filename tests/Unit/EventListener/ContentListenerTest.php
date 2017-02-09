<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\EventListener;

use Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter;
use Symfony\Cmf\Bundle\SeoBundle\EventListener\ContentListener;
use Symfony\Cmf\Bundle\SeoBundle\SeoPresentationInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ContentListenerTest extends \PHPUnit_Framework_Testcase
{
    protected $seoPresentation;
    protected $request;
    protected $event;
    protected $listener;

    public function setUp()
    {
        $this->seoPresentation = $this->createMock(SeoPresentationInterface::class);
        $this->request = $this->createMock(Request::class);
        $this->event = $this->createMock(GetResponseEvent::class);
        $this->listener = new ContentListener($this->seoPresentation, DynamicRouter::CONTENT_KEY);
    }

    /**
     * @dataProvider getRedirectRouteData
     */
    public function testRedirectRoute($targetUrl, $redirect = true, $currentPath = '/test')
    {
        $redirectResponse = $this->createMock(RedirectResponse::class);
        $redirectResponse->expects($this->any())
            ->method('getTargetUrl')
            ->will($this->returnValue($targetUrl));

        $content = new \stdClass();
        $this->seoPresentation
            ->expects($this->once())
            ->method('updateSeoPage')
            ->with($content)
        ;
        $this->seoPresentation
            ->expects($this->any())
            ->method('getRedirectResponse')
            ->will($this->returnValue($redirectResponse))
        ;

        $this->event
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($this->request))
        ;
        if ($redirect) {
            $this->event
                ->expects($this->once())
                ->method('setResponse')
                ->with($redirectResponse)
            ;
        } else {
            $this->event
                ->expects($this->never())
                ->method('setResponse')
            ;
        }

        $attributes = $this->createMock(ParameterBag::class);
        $attributes
            ->expects($this->any())
            ->method('has')
            ->with(DynamicRouter::CONTENT_KEY)
            ->will($this->returnValue(true))
        ;
        $attributes
            ->expects($this->any())
            ->method('get')
            ->with(DynamicRouter::CONTENT_KEY)
            ->will($this->returnValue($content))
        ;
        $this->request->attributes = $attributes;
        $this->request
            ->expects($this->any())
            ->method('getBaseUrl')
            ->will($this->returnValue(''))
        ;
        $this->request
            ->expects($this->any())
            ->method('getPathInfo')
            ->will($this->returnValue($currentPath))
        ;

        $this->listener->onKernelRequest($this->event);
    }

    public function getRedirectRouteData()
    {
        return [
            ['/test_redirect'],
            ['/a/test'],
            ['/test', false],
            ['/test?a', false],
            ['/test', false, '/test#b'],
            ['/test?a', false, '/test#b'],
        ];
    }
}
