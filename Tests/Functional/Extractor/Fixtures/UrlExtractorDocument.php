<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Extractor\Fixtures;

use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalRouteInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalUrlInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;

abstract class UrlExtractorDocument implements SeoOriginalUrlInterface, SeoAwareInterface
{

}
