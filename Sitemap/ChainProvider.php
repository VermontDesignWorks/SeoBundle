<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ChainProvider
{
    /**
     * @var UrlInformationProviderInterface[]
     */
    private $providers = array();

    /**
     * @return UrlInformationProviderInterface[]
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * @param UrlInformationProviderInterface[] $providers
     */
    public function setProviders($providers)
    {
        $this->providers = $providers;
    }

    public function addProvider(UrlInformationProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }
}
