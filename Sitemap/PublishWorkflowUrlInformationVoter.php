<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishWorkflowChecker;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\VoterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class PublishWorkflowUrlInformationVoter implements VoterInterface
{
    /**
     * @var SecurityContextInterface
     */
    private $publishWorkflowChecker;

    public function __construct(SecurityContextInterface $publishWorkflowChecker)
    {
        $this->publishWorkflowChecker = $publishWorkflowChecker;
    }
    /**
     * {@inheritDoc}
     */
    public function exposeOnSitemap($content, $sitemap = 'default')
    {
        return $this->publishWorkflowChecker->isGranted(PublishWorkflowChecker::VIEW_ATTRIBUTE, $content);
    }
}
