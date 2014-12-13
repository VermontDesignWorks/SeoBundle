<?php

namespace Symfony\Cmf\SeoBundle\Tests\Unit\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\RegisterUrlInformationProviderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class RegisterUrlInformationProviderPassTest extends AbstractCompilerPassTestCase
{

    /**
     * Register the compiler pass under test, just like you would do inside a bundle's load()
     * method:
     *
     *   $container->addCompilerPass(new MyCompilerPass());
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterUrlInformationProviderPass());
    }

    public function testTags()
    {
        $nonProviderService = new Definition();
        $this->setDefinition('some_service', $nonProviderService);

        $providerService = new Definition();
        $providerService->addTag('cmf_seo.sitemap.url_information_provider');
        $this->setDefinition('provider_service', $providerService);

        $chainProvider = new Definition();
        $this->setDefinition('cmf_seo.sitemap.chain_provider', $chainProvider);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.sitemap.chain_provider',
            'addProvider',
            array(new Reference('provider_service'))
        );
    }
}
