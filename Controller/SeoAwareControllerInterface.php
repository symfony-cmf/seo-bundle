<?php
/**
 * User: maximilian
 * Date: 2/7/14
 * Time: 11:34 PM
 * 
 */

namespace Cmf\SeoBundle\Controller;


use Cmf\SeoBundle\Services\CmfSeoPageInterface;

interface SeoAwareControllerInterface
{

    /**
     * To get the cmf seo page service into the controller, the method
     * has to be called
     *
     * @param CmfSeoPageInterface $seoPage
     */
    public function setSeoPage(CmfSeoPageInterface $seoPage);
}
