<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoExtractorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * The abstract class for the SeoPresentation Model.
 *
 * It contains all needed setters for the DI and the some helpers for getting
 * locale, document manager and the SeoMetadata.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
abstract class AbstractSeoPresentation implements SeoPresentationInterface
{
    /**
     * Storing the content parameters - config values under cmf_seo.content.
     *
     * @var array
     */
    protected $contentParameters;

    /**
     * Storing the title parameters - config values under cmf_seo.title.
     *
     * @var array
     */
    protected $titleParameters;

    /**
     * @var bool
     */
    protected $redirectResponse = false;

    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var SeoAwareInterface
     */
    protected $contentDocument;

    /**
     * @var SeoExtractorInterface[]
     */
    protected $strategies = array();

    /**
     * Setter for the redirectResponse property.
     *
     * @param RedirectResponse $redirect
     */
    protected function setRedirectResponse(RedirectResponse $redirect)
    {
        $this->redirectResponse = $redirect;
    }

    /**
     * {@inheritDoc}
     */
    public function getRedirectResponse()
    {
        return $this->redirectResponse;
    }

    /**
     * This method is needed to get the default title parameters injected. They are used for
     * concatenating the default values and the seo meta data or defining the pattern for that.
     *
     * @param array $titleParameters
     */
    public function setTitleParameters(array $titleParameters)
    {
        $this->titleParameters = $titleParameters;
    }

    /**
     * This method is the setter injection for the content parameters which contain strategies for
     * duplicate content.
     *
     * @param array $contentParameters
     */
    public function setContentParameters(array $contentParameters)
    {
        $this->contentParameters = $contentParameters;
    }

    /**
     * The document manager is needed to detect the current locale of the document.
     *
     * @param \Doctrine\Bundle\PHPCRBundle\ManagerRegistry $managerRegistry
     */
    public function setDoctrineRegistry(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * Setter for the default locale of the application.
     *
     * If the list of translated titles does not contain the locale of the current document,
     * or the current document has no locale at all, this locale is used instead.
     *
     * @param $locale
     */
    public function setDefaultLocale($locale)
    {
        $this->defaultLocale = $locale;
    }

    /**
     * {@inheritDoc}
     */
    public function setContentDocument($contentDocument)
    {
        $this->contentDocument = $contentDocument;
    }

    /**
     * To get the Document Manager out of the registry, this method needs to be called.
     *
     * @return ObjectManager|DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->managerRegistry->getManager();
    }

    /**
     * Get the applications default locale.
     *
     * @return string
     */
    protected function getApplicationDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * This method uses the DocumentManager to get the documents current locale.
     * @param string
     * @return null|string
     */
    protected function getModelLocale()
    {
        return $this->getDocumentManager()->getUnitOfWork()->getCurrentLocale($this->contentDocument);
    }

    /**
     * Method to add strategies by the compiler pass.
     *
     * @param SeoExtractorInterface $strategy
     */
    public function addExtractor(SeoExtractorInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }
}
