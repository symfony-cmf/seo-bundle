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

use Symfony\Cmf\Bundle\SeoBundle\EventListener\LanguageListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class LanguageListenerTest extends \PHPUnit_Framework_Testcase
{
    private $listener;

    protected function setUp()
    {
        $this->listener = new LanguageListener();
    }

    /**
     * @dataProvider provideRequestLocales
     */
    public function testSetsContentLanguageHeader($locale)
    {
        $request = Request::create('/');
        $request->setLocale($locale);

        $response = new Response();

        $event = $this->createMock(FilterResponseEvent::class);
        $event->expects($this->any())->method('getRequest')->will($this->returnValue($request));
        $event->expects($this->any())->method('getResponse')->will($this->returnValue($response));

        $this->listener->onKernelResponse($event);

        $this->assertEquals('en', $response->headers->get('Content-Language'));
    }

    public function provideRequestLocales()
    {
        return [
            ['en'],
            ['en_US'],
        ];
    }

    public function testDoesNotOverridePreSetContentLanguage()
    {
        $request = Request::create('/');
        $request->setLocale('en');

        $response = new Response();
        $response->headers->set('Content-Language', 'nl');

        $event = $this->createMock(FilterResponseEvent::class);
        $event->expects($this->any())->method('getRequest')->will($this->returnValue($request));
        $event->expects($this->any())->method('getResponse')->will($this->returnValue($response));

        $this->listener->onKernelResponse($event);

        $this->assertEquals('nl', $response->headers->get('Content-Language'));
    }
}
