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
