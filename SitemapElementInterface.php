<?php

namespace Symfony\Cmf\Bundle\SeoBundle;

/**
 * Documents persisted with phpcr-odm should implement that interface.
 * They also need to create a property with the name "visible_for_sitemap" to be recognized
 * by the default provider, that generates the UrlInformation object.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SitemapElementInterface
{
    /**
     * Decision whether a document should be visible
     * in sitemap or not.
     *
     * @return bool
     */
    public function isVisibleInSitemap();
}
