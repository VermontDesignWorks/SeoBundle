<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Controller;

use Symfony\Cmf\Bundle\SeoBundle\Controller\SitemapController;
use Symfony\Cmf\Bundle\SeoBundle\Model\AlternateLocale;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\SitemapRouteProviderInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SitemapRouteProviderInterface
     */
    private $generator;

    /**
     * @var SitemapController
     */
    private $controller;

    public function setUp()
    {
        $this->generator = $this->getMock('Symfony\Cmf\Bundle\SeoBundle\SitemapRouteProviderInterface');
        $this->createRoutes();

        $this->controller = new SitemapController($this->generator);
    }

    public function testRequestJson()
    {
        /** @var Response $response */
        $response = $this->controller->indexAction('json');
        $expected = array(
            array(
                'loc'               => 'http://www.test-alternate-locale.de',
                'label'             => 'Test alternate locale',
                'changefreq'        => 'never',
                'lastmod'           => '2014-11-07T00:00:00+01:00',
                'priority'          => 0.85,
                'alternate_locales' => array(
                    array('href' => 'http://www.test-alternate-locale.com', 'href_locale' => 'en')
                ),
            ),
            array(
                'loc'               => 'http://www.test-domain.de',
                'label'             => 'Test label',
                'changefreq'        => 'always',
                'lastmod'           => '2014-11-06T00:00:00+01:00',
                'priority'          => 0.85,
                'alternate_locales' => array(),
            ),
        );

        $this->assertEquals($expected, json_decode($response->getContent(), true));
    }

    public function testRequestXml()
    {
        // preparing stuff
        $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerInterface');
        $this->controller->setContainer($container);
        $templating = $this->getMock('EngineInterface', array('render'));
        $container
            ->expects($this->any())
            ->method('get')
            ->with($this->equalTo('templating'))
            ->will($this->returnValue($templating))
        ;
        $response = new Response('some-xml-string');
        $templating->expects($this->once())->method('render')->will($this->returnValue($response));

        /** @var Response $response */
        $response = $this->controller->indexAction('xml');

        $this->assertEquals('some-xml-string', $response->getContent());
    }

    public function testRequestHtml()
    {
        // preparing stuff
        $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerInterface');
        $this->controller->setContainer($container);
        $templating = $this->getMock('EngineInterface', array('render'));
        $container
            ->expects($this->any())
            ->method('get')
            ->with($this->equalTo('templating'))
            ->will($this->returnValue($templating))
        ;
        $response = new Response('some-html-string');
        $templating->expects($this->once())->method('render')->will($this->returnValue($response));

        /** @var Response $response */
        $response = $this->controller->indexAction('html');

        $this->assertEquals('some-html-string', $response->getContent());
    }

    private function createRoutes()
    {
        $urls = array();

        $simpleUrl = new UrlInformation();
        $simpleUrl
            ->setLoc('http://www.test-domain.de')
            ->setChangeFreq('always')
            ->setLabel('Test label')
            ->setPriority(0.85)
            ->setLastmod(new \DateTime('2014-11-06', new \DateTimeZone('Europe/Berlin')))
        ;

        $urlWithAlternateLocale = new UrlInformation();
        $urlWithAlternateLocale
            ->setLoc('http://www.test-alternate-locale.de')
            ->setChangeFreq('never')
            ->setLabel('Test alternate locale')
            ->setPriority(0.85)
            ->setLastmod(new \DateTime('2014-11-07', new \DateTimeZone('Europe/Berlin')))
        ;
        $alternateLocale = new AlternateLocale('http://www.test-alternate-locale.com', 'en');
        $urlWithAlternateLocale->addAlternateLocale($alternateLocale);

        $urls[] = $urlWithAlternateLocale;
        $urls[] = $simpleUrl;

        $this->generator->expects($this->any())->method('generateRoutes')->will($this->returnValue($urls));
    }

    private function getFileContent($type)
    {
        $basePath = __DIR__.'/../../Resources/Fixtures/sitemap/sitemap';

        return file_get_contents($basePath.'.'.$type);
    }
}
