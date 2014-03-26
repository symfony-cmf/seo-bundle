<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Exceptions;

/**
 * This class wraps "document not supported" exceptions that occurs in the
 * extractors.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class DocumentNotSupport extends SeoExtractorStrategyException
{
    public function __construct($document, $code = 0, \Exception $previous = null)
    {
        parent::__construct(
            sprintf(
                'The given document "%s" is not supported by this strategy, call the supports() method first.',
                is_object($document) ? get_class($document) : $document
            ),
            $code,
            $previous
        );
    }
}
