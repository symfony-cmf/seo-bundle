<?php
/**
 * User: maximilian
 * Date: 2/7/14
 * Time: 11:34 PM
 * 
 */

namespace Cmf\SeoBundle\Controller;


use Cmf\SeoBundle\Model\SeoPresentationInterface;

interface SeoAwareControllerInterface
{

    /**
     * To get the cmf seo page service into the controller, the method
     * has to be called
     *
     * @param SeoPresentationInterface $seoPage
     * @return
     */
    public function setSeoPage(SeoPresentationInterface $seoPage);
}
