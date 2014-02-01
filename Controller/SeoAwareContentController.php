<?php

namespace Cmf\SeoBundle\Controller;

use Cmf\SeoBundle\Model\SeoAwareContentInterface;
use Cmf\SeoBundle\Model\SeoStuff;
use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;

/**
 * The content controller is a simple controller that calls a template with
 * the specified content.
 */
class SeoAwareContentController extends  Controller
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var string
     */
    protected $defaultTemplate;

    /**
     * @var ViewHandlerInterface
     */
    protected $viewHandler;

    /**
     * Instantiate the content controller.
     *
     * @param EngineInterface $templating the templating instance to render the
     *      template
     * @param string $defaultTemplate default template to use in case none is
     *      specified explicitly
     * @param ViewHandlerInterface $viewHandler optional view handler instance
     */
    public function __construct(EngineInterface $templating, $defaultTemplate, ViewHandlerInterface $viewHandler = null)
    {
        $this->templating = $templating;
        $this->defaultTemplate = $defaultTemplate;
        $this->viewHandler = $viewHandler;
    }

    /**
     * Render the provided content.
     *
     * When using the publish workflow, enable the publish_workflow.request_listener
     * of the core bundle to have the contentDocument as well as the route
     * checked for being published.
     * We don't need an explicit check in this method.
     *
     * @param Request $request
     * @param object  $contentDocument
     * @param string  $contentTemplate symfony path of the template to render the
     *      content document. if omitted uses the defaultTemplate as injected
     *      in constructor
     *
     * @return Response
     */
    public function indexAction(Request $request, $contentDocument, $contentTemplate = null)
    {
        $contentTemplate = $contentTemplate ?: $this->defaultTemplate;

        $contentTemplate = str_replace(
            array('{_format}', '{_locale}'),
            array($request->getRequestFormat(), $request->getLocale()),
            $contentTemplate
        );

        $params = $this->getParams($request, $contentDocument);

        //additional stuff for rendering Seo Stuff
        if ($contentDocument instanceof SeoAwareContentInterface ) {
            $this->handleSeoStuff($contentDocument);
        }

        return $this->renderResponse($contentTemplate, $params);

    }

    protected function renderResponse($contentTemplate, $params)
    {
        if ($this->viewHandler) {
            if (1 === count($params)) {
                $templateVar = key($params);
                $params = reset($params);
            }
            $view = new View($params);
            if (isset($templateVar)) {
                $view->setTemplateVar($templateVar);
            }
            $view->setTemplate($contentTemplate);

            return $this->viewHandler->handle($view);
        }

        return $this->templating->renderResponse($contentTemplate, $params);
    }

    protected function getParams(Request $request, $contentDocument)
    {
        return array(
            'cmfMainContent' => $contentDocument,
        );
    }

    protected function handleSeoStuff(SeoAwareContentInterface $contentDocument)
    {
        /** @var SeoPage $seoPage */
        $seoPage = $this->get('sonata.seo.page');

        /** @var SeoStuff $seoStuff */
        $seoStuff = $contentDocument->getSeoStuff();


        //set the title based on the title strategy
        $title = $this->createTitle($seoStuff, $seoPage->getTitle());
        $seoPage->setTitle($title);
        $seoPage->addMeta('property', 'og:title', $title);

        $seoPage->addMeta('property', 'og:description', $seoStuff->getMetaDescription());
        $seoPage->addMeta('name', 'description', $seoStuff->getMetaDescription());
        $seoPage->addMeta('property', 'og:keys', $seoStuff->getMetaKeywords());
    }

    /**
     * based on the title strategy this method will create the title from the given
     * configs in the seo configuration part
     *
     * @param \Cmf\SeoBundle\Model\SeoStuff $seoStuff
     * @param null $configTitle
     * @internal param \Cmf\SeoBundle\Model\SeoAwareContentInterface $contentDocument
     * @return string
     */
    protected function createTitle(SeoStuff $seoStuff, $configTitle = null)
    {
        $contentTitle = $seoStuff->getTitle();

        switch ($seoStuff->getTitleStrategy()) {
            case 'prepend':
                return $contentTitle. ' - '.$configTitle;
            case 'append':
                return $configTitle . ' - ' . $contentTitle;
            case 'replace':
                return $contentTitle;
            default:
                return $configTitle;
        }
    }
}
