<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Cmf\Bundle\SeoBundle\SitemapRouteGenerator as SitemapRouteGeneratorInterface;

/**
 * The PHPCR implementation of the sitemap route generator.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapRouteGenerator implements SitemapRouteGeneratorInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritDocs}
     */
    public function generateRoutes()
    {
        return array();
    }
}
 