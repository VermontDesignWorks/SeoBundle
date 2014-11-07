<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlElement;
use Symfony\Cmf\Bundle\SeoBundle\SitemapRouteGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * This controller will handle all request for the sitemap.
 *
 * Depending on the type the controller is able to respond
 * json, xml or html string.
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapController extends Controller
{
    const TEMPLATE_HTML = 'CmfSeoBundle:Sitemap:index.html.twig';
    /**
     * @var
     */
    private $routeGenerator;

    /**
     * @param SitemapRouteGenerator $routeGenerator
     */
    public function __construct(SitemapRouteGenerator $routeGenerator)
    {
        $this->routeGenerator = $routeGenerator;
    }

    /**
     * @param $type
     *
     * @return Response
     */
    public function indexAction($type)
    {
        $response = null;
        $urls = $this->routeGenerator->generateRoutes();

        switch ($type) {
            case 'json':
                $response = $this->createJsonResponse($urls);
                break;
            case 'xml':
                $response =  $this->createXmlResponse($urls);
                break;
            default:
                $response =  $this->renderView(
                    self::TEMPLATE_HTML,
                    array('urls' => $urls)
                );
                break;
        }

        return $response;
    }

    /**
     * @param array|UrlElement[] $urls
     *
     * @return JsonResponse
     */
    private function createJsonResponse($urls)
    {
        $result = array();

        foreach ($urls as $url) {
            $result[] = $url->toArray();
        }

        return new JsonResponse($result);
    }

    /**
     * @param array|UrlElement[] $urls
     *
     * @return Response
     */
    private function createXmlResponse($urls)
    {
        $sitemap = new \DOMDocument();

        $root = $sitemap->createElement("urlset");
        $sitemap->appendChild($root);

        $rootAttribute = $sitemap->createAttribute('xmlns');
        $root->appendChild($rootAttribute);
        $rootAttributeContent = $sitemap->createTextNode('http://www.sitemaps.org/schemas/sitemap/0.9');
        $rootAttribute->appendChild($rootAttributeContent);

        $rootAttribute = $sitemap->createAttribute('xmlns:xhtml');
        $root->appendChild($rootAttribute);
        $rootAttributeContent = $sitemap->createTextNode('http://www.w3.org/1999/xhtml');
        $rootAttribute->appendChild($rootAttributeContent);

        foreach ($urls as $url) {
            $urlElement = $sitemap->createElement("url");
            $root->appendChild($urlElement);

            $loc = $sitemap->createElement("loc");
            $lastmod = $sitemap->createElement("lastmod");
            $changefreq = $sitemap->createElement("changefreq");

            $urlElement->appendChild($loc);
            $locContent = $sitemap->createTextNode($url->getLoc());
            $loc->appendChild($locContent);

            $urlElement->appendChild($lastmod);
            $lastmodContent = $sitemap->createTextNode($url->getLastmod());
            $lastmod->appendChild($lastmodContent);

            $urlElement->appendChild($changefreq);
            $changefreqContent = $sitemap->createTextNode($url->getChangeFreq());
            $changefreq->appendChild($changefreqContent);

            foreach ($url->getAlternateLocales() as $alternateLocale) {
                $localeElement = $sitemap->createElement('xhtml:link');
                $localeElement->setAttribute('rel', 'alternate');
                $localeElement->setAttribute('hreflang', $alternateLocale->hrefLocale);
                $localeElement->setAttribute('href', $alternateLocale->href);
                $urlElement->appendChild($localeElement);
            }

        }

        return new Response($sitemap->saveXML());
    }
}
