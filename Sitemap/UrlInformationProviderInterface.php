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

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;

/**
 * Providers are able to create a list of UrlInformation for the sitemap creation.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface UrlInformationProviderInterface
{
    /**
     * @return UrlInformation[]
     */
    public function getUrlInformation();
}
