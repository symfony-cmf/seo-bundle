<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Doctrine\ODM\PHPCR\DocumentManager;

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
     * action like the redirect.
     */
    public function setMetadataValues();

    /**
     * Will return false if the strategy for duplicate content is not redirect, or it is
     * but there is not redirect route in the meta data.
     *
     * @return bool|string
     */
    public function getRedirect();

    /**
     * This method is needed to get the default title parameters injected. They are used for
     * concatenating the default values and the seo meta data or defining the strategy for that.
     *
     * @param array $titleParameters
     */
    public function setTitleParameters(array $titleParameters);

    /**
     * This method is the setter injection for the content parameters which contain strategies for
     * duplicate content.
     *
     * @param array $contentParameters
     */
    public function setContentParameters(array $contentParameters);

    /**
     * The document manager is needed to detect the current locale of the document.
     *
     * @param \Doctrine\ODM\PHPCR\DocumentManager $documentManager
     */
    public function setDocumentManager(DocumentManager $documentManager);

    /**
     * Setter for the default locale of the application.
     *
     * This one is used, if the document locale can not be found in the list
     * of translated default titles.
     *
     * @param $locale
     */
    public function setDefaultLocale($locale);
}
