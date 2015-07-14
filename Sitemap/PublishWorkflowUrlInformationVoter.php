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
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
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

    /**
     * {@inheritDocs}
     */
    public function supportsAttribute($attribute)
    {
        return 'sitemap' === $attribute;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return true;
    }

    /**
     * Returns the vote for the given parameters.
     *
     * This method must return one of the following constants:
     * ACCESS_GRANTED, ACCESS_DENIED, or ACCESS_ABSTAIN.
     *
     * @param TokenInterface $token A TokenInterface instance
     * @param object|null $object The object to secure
     * @param array $attributes An array of attributes associated with the method being invoked
     *
     * @return int either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        // TODO: Implement vote() method.
    }
}
