<?php

namespace Symfony\Cmf\Bundle\SeoBundle;

use Symfony\Component\Routing\Route;

/**
 * Interface for all sitemap route generators.
 *
 * The CMF provides one to get the routes of content documents
 * persisted with the PHPCR. But you should be able to create your
 * own by implementing this interface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SitemapRouteGenerator
{
    /**
     * Creates an array of route objects, which should be shown in a sitemap.
     *
     * @return array|Route
     */
    public function generateRoutes();
}
 