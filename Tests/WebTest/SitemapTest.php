<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\WebTest;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Client;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapTest extends BaseTestCase
{
    /** @var  Client */
    private $client;

    public function setUp()
    {
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr\LoadContentData',
        ));
        $this->client = $this->createClient();
    }

    public function testSitmapHtml()
    {
        $crawler = $this->client->request('GET', '/sitemap.html');
        $res = $this->client->getResponse();

        $this->assertEquals(200, $res->getStatusCode());
    }
}
