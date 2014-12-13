<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Sitemap;

use Doctrine\ODM\PHPCR\DocumentManager;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SitemapUrlInformationProvider;
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

        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr\LoadSitemapData',
        ));

        $this->provider = new SitemapUrlInformationProvider(
            $this->dm,
            $this->getContainer()->get('router'),
            $this->getContainer()->get('cmf_core.publish_workflow.checker')
        );
    }

    public function testRouteGeneration()
    {
        $routeInformation = $this->provider->generateRoutes();

        $this->assertCount(2, $routeInformation);
    }
}
