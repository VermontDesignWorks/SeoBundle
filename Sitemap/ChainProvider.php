<?php

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
