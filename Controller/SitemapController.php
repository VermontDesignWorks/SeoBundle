<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Controller;

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\ChainProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

/**
 * Controller to handle requests for sitemap.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapController
{
    const TEMPLATE_HTML = 'CmfSeoBundle:Sitemap:index.html.twig';

    const TEMPLATE_XML = 'CmfSeoBundle:Sitemap:index.xml.twig';

    /**
     * @var ChainProvider
     */
    private $chainProvider;
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @param ChainProvider $routeGenerator
     * @param EngineInterface $templating
     */
    public function __construct(ChainProvider $routeGenerator, EngineInterface $templating)
    {
        $this->chainProvider = $routeGenerator;
        $this->templating = $templating;
    }

    /**
     * @param $_format
     *
     * @return Response
     */
    public function indexAction($_format)
    {
        $response = null;
        $urls = array();
        foreach ($this->chainProvider->getProviders() as $provider) {
            $urls = array_merge($urls, $provider->generateRoutes());
        }

        if ('json' === $_format) {
            $response = $this->createJsonResponse($urls);
        } elseif ('xml' === $_format || 'html' === $_format) {
            $template = 'xml' === $_format ? self::TEMPLATE_XML : self::TEMPLATE_HTML;
            $response =  new Response($this->templating->render($template, array('urls' => $urls)));
        }

        if (null === $response) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Unsupported type %s for sitemap creation. Use one of %s',
                    $_format,
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
