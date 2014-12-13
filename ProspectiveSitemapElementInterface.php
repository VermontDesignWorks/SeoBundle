<?php

namespace Symfony\Cmf\Bundle\SeoBundle;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface ProspectiveSitemapElementInterface
{
    /**
     * Decision whether a document should be visible
     * in sitemap or not.
     *
     * @return bool
     */
    public function isIsVisibleForSitemap();
}
