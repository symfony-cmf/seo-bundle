<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\ErrorHandling;

use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException;

/**
 * This presentation model enriches the the values to render the
 * error pages by the help of so called BestMatcher.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class BestMatchPresentation extends ExceptionController
{
    /**
     * Type/shortcut for a best matcher handling the parent.
     */
    const MATCH_TYPE_PARENT = 'parent';

    /**
     * Type/shortcut for best matcher handling the ancestors
     */
    const MATCH_TYPE_ANCESTOR = 'ancestor';

    /**
     * Chain of matcher.
     *
     * @var array|BestMatcherInterface[]
     */
    protected $matcherChain = array();

    public function createMatches()
    {

    }

    public function addMatcher(BestMatcherInterface $matcher, $type)
    {
        if (self::MATCH_TYPE_ANCESTOR !== $type && self::MATCH_TYPE_PARENT !== $type) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s is not supported as a matcher type. use one of %s or %s',
                    $type,
                    self::MATCH_TYPE_PARENT,
                    self::MATCH_TYPE_ANCESTOR
                )
            );
        }

        if (array_key_exists($type, $this->matcherChain)) {
            throw new InvalidArgumentException(sprintf('You can only add on matcher with type %s.', $type));
        }

        $this->matcherChain[$type] = $matcher;
    }
}
