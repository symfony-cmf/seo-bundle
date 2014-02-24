<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    public function indexAction($contentDocument)
    {
        $params = array(
            'cmfMainContent' => array(
                'title'     => $contentDocument->getTitle(),
                'body' => $contentDocument->getBody(),
            )
        );

        return $this->render('::tests/index.html.twig', $params);
    }
}
