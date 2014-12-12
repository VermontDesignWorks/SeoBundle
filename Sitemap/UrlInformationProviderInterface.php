<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Sitemap;

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;

/**
 * Interface for a provider that is able to create a list of
 * UrlInformation for sitemap creation.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface UrlInformationProviderInterface
{
    /**
     * @return UrlInformation[]
     */
    public function generateRoutes();
}
