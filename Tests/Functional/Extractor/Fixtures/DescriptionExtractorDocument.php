<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Extractor\Fixtures;

use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoDescriptionExtractorInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;

abstract class DescriptionExtractorDocument implements SeoDescriptionExtractorInterface, SeoAwareInterface
{

}
