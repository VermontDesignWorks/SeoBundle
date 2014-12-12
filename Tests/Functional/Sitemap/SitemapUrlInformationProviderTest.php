<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Sitemap;

use Doctrine\ODM\PHPCR\DocumentManager;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SitemapUrlInformationProvider;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\ContentBase;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\SitemapAwareContent;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapUrlInformationProviderTest extends BaseTestCase
{
    /**
     * @var DocumentManager
     */
    protected $dm;
    protected $base;

    /**
     * @var SitemapUrlInformationProvider
     */
    protected $provider;

    public function setUp()
    {
        $this->db('PHPCR')->createTestNode();
        $this->dm = $this->db('PHPCR')->getOm();
        $this->base = $this->dm->find(null, '/test');

        $this->provider = new SitemapUrlInformationProvider($this->dm, $this->getContainer()->get('router'));
    }

    public function testRouteGeneration()
    {
        $sitemapAwareContent = new SitemapAwareContent();
        $sitemapAwareContent
            ->setIsVisibleForSitemap(true)
            ->setTitle('Sitemap Aware Content')
            ->setName('sitemap-aware')
            ->setParentDocument($this->dm->find(null, '/test'))
            ->setBody('Content for that is sitemap aware')
        ;
        $this->dm->persist($sitemapAwareContent);

        $route = new Route();
        $route->setParent($this->dm->find(null, '/test'));
        $route->setName('test-sitemap');
        $route->setContent($sitemapAwareContent);
        $this->dm->persist($route);

        $simpleContent = new ContentBase();
        $simpleContent
            ->setTitle('Content not on sitemap')
            ->setName('non-sitemap')
            ->setParentDocument($this->dm->find(null, '/test'))
            ->setBody('Content for non matching content');

        $this->dm->flush();

        $routeInformation = $this->provider->generateRoutes();


        $this->assertCount(1, $routeInformation);
    }
}
