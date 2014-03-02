<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Extractor\Fixtures;

use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoTitleExtractorInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;

abstract class TitleExtractorDocument implements SeoTitleExtractorInterface, SeoAwareInterface
{

}
