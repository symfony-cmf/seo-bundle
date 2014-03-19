<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * An interface for the SeoPresentation model.
 *
 * It serves methods to set the title and content parameters
 * from the container/configuration and a method for setting
 * the SeoMetadata.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoPresentationInterface
{
    /**
     * To get access to the current content and it's SeoMetadata
     * this setter is needed.
     *
     * @param SeoAwareInterface $contentDocument
     */
    public function setContentDocument(SeoAwareInterface $contentDocument);

    /**
     * Just a method which will set the values to the sonata service or trigger some other
     * action like the redirectResponse.
     */
    public function setMetadataValues();

    /**
     * Will return false if the strategy for duplicate content is not redirectResponse, or it is
     * but there is not redirectResponse route in the meta data.
     *
     * @return bool|RedirectResponse
     */
    public function getRedirectResponse();
}
