<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document;

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;
use Symfony\Component\Routing\Route;

/**
 * @PHPCRODM\Document(referenceable=true)
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapAwareContent extends ContentBase implements RouteReferrersReadInterface
{
    private $routes = array();

    /**
     * @var bool
     *
     * @PHPCRODM\Boolean(property="visible")
     */
    private $isVisibleForSitemap;


    /**
     * @return boolean
     */
    public function isIsVisibleForSitemap()
    {
        return $this->isVisibleForSitemap;
    }

    /**
     * @param boolean $isVisibleForSitemap
     *
     * @return SitemapAwareContent
     */
    public function setIsVisibleForSitemap($isVisibleForSitemap)
    {
        $this->isVisibleForSitemap = $isVisibleForSitemap;

        return $this;
    }

    /**
     * Get the routes that point to this content.
     *
     * @return Route[] Route instances that point to this content
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
