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

    const TEMPLATE_XML = 'CmfSeoBundle:Sitemap:index.xml.twig';

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

        if ('json' === $type) {
            $response = $this->createJsonResponse($urls);
        } elseif ('xml' === $type || 'html' === $type) {
            $template = 'xml' === $type ? self::TEMPLATE_XML : self::TEMPLATE_HTML;
            $response =  $this->renderView($template, array('urls' => $urls));
        }

        if (null === $response) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Unsupported type %s for sitemap creation. Use one of %s',
                    $type,
                    implode(', ', array('xml', 'json', 'html'))
                )
            );
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
}
