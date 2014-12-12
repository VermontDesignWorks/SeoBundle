<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\ChainProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller to handle requests for sitemap.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapController extends Controller
{
    const TEMPLATE_HTML = 'CmfSeoBundle:Sitemap:index.html.twig';

    const TEMPLATE_XML = 'CmfSeoBundle:Sitemap:index.xml.twig';

    /**
     * @var ChainProvider
     */
    private $chainProvider;

    /**
     * @param ChainProvider $routeGenerator
     */
    public function __construct(ChainProvider $routeGenerator)
    {
        $this->chainProvider = $routeGenerator;
    }

    /**
     * @param $type
     *
     * @return Response
     */
    public function indexAction($type)
    {
        $response = null;
        $urls = array();
        foreach ($this->chainProvider->getProviders() as $provider) {
            $urls = array_merge($urls, $provider->generateRoutes());
        }

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
     * @param array|UrlInformation[] $urls
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
