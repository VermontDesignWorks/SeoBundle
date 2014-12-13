<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Functional\Doctrine\Phpcr;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\DocumentManager;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SitemapUrlInformationProvider;
use Symfony\Cmf\Bundle\SeoBundle\Model\AlternateLocale;
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

    protected $alternateLocaleProvider;

    protected $logger;

    public function setUp()
    {
        $this->db('PHPCR')->createTestNode();
        $this->dm = $this->db('PHPCR')->getOm();
        $this->base = $this->dm->find(null, '/test');

        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr\LoadSitemapData',
        ));

        $this->logger = $this->getMock('Psr\Log\LoggerInterface');

        $this->provider = new SitemapUrlInformationProvider(
            $this->dm,
            $this->getContainer()->get('router'),
            $this->getContainer()->get('cmf_core.publish_workflow.checker'),
            'always',
            $this->logger
        );
        $this->alternateLocaleProvider = $this->getMock('\Symfony\Cmf\Bundle\SeoBundle\AlternateLocaleProviderInterface');
        $this->provider->setAlternateLocaleProvider($this->alternateLocaleProvider);

        $alternateLocale = new AlternateLocale('test', 'de');
        $this->alternateLocaleProvider
            ->expects($this->any())
            ->method('createForContent')
            ->will($this->returnValue(new ArrayCollection(array($alternateLocale))));
    }

    public function testRouteGeneration()
    {
        $routeInformation = $this->provider->generateRoutes();

        $this->assertCount(2, $routeInformation);
    }
}
