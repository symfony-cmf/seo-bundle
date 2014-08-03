<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Controller;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ODM\PHPCR\Query\Builder\QueryBuilder;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController as TwigExceptionController;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * ExceptionController for providing custom error pages with
 * an suggestion for best matching routes.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ExceptionController extends TwigExceptionController
{
    /**
     * @var ManagerRegistry To find best match routes.
     */
    private $managerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function setManagerRegistry($managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * Converts an Exception to a Response.
     *
     * For RouteNotFoundException this method will
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     *
     * @return Response
     *
     */
    public function onKernelException (GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if (!$exception instanceof NotFoundHttpException) {
            return;
        }

        $request = $event->getRequest();
        $currentUri = explode('/', $request->getUri());
        $lastPart = end($currentUri); // todo maybe use for equal matches
        $parentUri = implode('/', array_pop($currentUri));

        $routeRepository = $this->managerRegistry
            ->getRepository('Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route');

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $routeRepository->createQueryBuilder('r');
        $queryBuilder->getChildren();
        $queryBuilder->where('r.id = '.$parentUri);
        $routes = $queryBuilder->getQuery();

        $code = $exception->getStatusCode();

        $response = new Response($this->twig->render(
            $this->findTemplate($request, 'html', $code, $this->debug),
            array(
                'status_code'    => $code,
                'status_text'    => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                'exception'      => $exception,
                'best-matches'   => $this->createBestMatches($routes)
            )
        ));
        $response->setStatusCode(Response::HTTP_NOT_FOUND);

        $event->setResponse($response);
    }

    /**
     * @param RouteObjectInterface $routes
     * @return array
     */
    private function createBestMatches(RouteObjectInterface $routes)
    {
        $routeArray = array();


        return $routeArray;
    }
}
