<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishWorkflowChecker;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * A voter that checks whether the content is published, to integrate with the
 * symfony cmf core publication workflow.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class PublishWorkflowVoter implements VoterInterface
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
     * {@inheritdoc}
     */
    public function exposeOnSitemap($content, $sitemap)
    {
        return $this->publishWorkflowChecker->isGranted(PublishWorkflowChecker::VIEW_ATTRIBUTE, $content);
    }
}
