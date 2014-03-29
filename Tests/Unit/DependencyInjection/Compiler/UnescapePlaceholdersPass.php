<?php

namespace Symfony\Cmf\SeoBundle\Tests\Unit\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\RegisterExtractorsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterExtractorsPassTest extends AbstractCompilerPassTestCase
{
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterExtractorsPass(array(
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
