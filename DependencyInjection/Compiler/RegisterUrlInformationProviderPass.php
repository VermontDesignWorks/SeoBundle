<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class RegisterUrlInformationProviderPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     *
     * @throws LogicException If a tagged service is not public.
     */
    public function process(ContainerBuilder $container)
    {
        // feature not activated means nothing to add
        if (!$container->hasDefinition('cmf_seo.sitemap.chain_provider')) {
            return;
        }

        $chainProviderDefinition = $container->getDefinition('cmf_seo.sitemap.chain_provider');
        $taggedServices = $container->findTaggedServiceIds('cmf_seo.sitemap.url_information_provider');

        foreach ($taggedServices as $id => $attributes) {
            $definition = $container->getDefinition($id);
            if (!$definition->isPublic()) {
                throw new LogicException(sprintf('Matcher "%s" must be public.', $id));
            }

            $chainProviderDefinition->addMethodCall('addProvider', array(new Reference($id)));
        }
    }
}
