<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\SeoBundle\Tests\Unit\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\UnescapePlaceholdersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterExtractorsPassTest extends AbstractCompilerPassTestCase
{
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new UnescapePlaceholdersPass(array(
            'escaped_parameter',
            'unescaped_parameter',
        )));
    }

    public function testRegistersServicesWithExtractorTag()
    {
        $this->setParameter('escaped_parameter', 'Hello %%user%%!');
        $this->setParameter('user', 'World');
        $this->setParameter('unescaped_parameter', 'Hello %user%!');

        $this->compile();

        $this->assertContainerBuilderHasParameter('escaped_parameter', 'Hello %user%!');
        $this->assertContainerBuilderHasParameter('unescaped_parameter', 'Hello World!');
    }
}
