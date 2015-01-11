<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Controller;

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProviderInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
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
    /**
     * @var UrlInformationProviderInterface
*/
    private $urlProvider;
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var array
     */
    private $templates;

    /**
     * Inject the templates as an hashmap like:
     *
     * array('<format>' => 'Bundle:Domain:template.format.twig')
     *
     * Valid formats are html, json and xml.
     *
     * @param UrlInformationProviderInterface $provider
     * @param EngineInterface $templating
     * @param array $templates
     */
    public function __construct(
        UrlInformationProviderInterface $provider,
        EngineInterface $templating,
        array $templates
    ) {
        $this->urlProvider = $provider;
        $this->templating = $templating;
        $this->templates = $templates;
    }

    /**
     * @param string $_format The format of the sitemap. Supported values are html|xml|json
     *
     * @return Response
     */
    public function indexAction($_format)
    {
        $response = null;
        $urls = $this->urlProvider->generateRoutes();
        if (isset($this->templates[$_format])) {
            $response =  new Response($this->templating->render($this->templates[$_format], array('urls' => $urls)));
        } elseif ('json' === $_format) {
            $response = $this->createJsonResponse($urls);
        }

        if (null === $response) {
            $supportedFormats = array_keys($this->templates);
            $supportedFormats[] = 'json';
            throw new InvalidConfigurationException(
                sprintf(
                    'Type %s is not configures for sitemaps, use one of %s',
                    $_format,
                    implode(', ', $supportedFormats)
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
