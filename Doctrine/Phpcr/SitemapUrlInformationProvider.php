<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Query\QueryInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProviderInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * The PHPCR implementation of the sitemap route generator.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapUrlInformationProvider implements UrlInformationProviderInterface
{
    /**
     * @var DocumentManager
     */
    private $manager;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param DocumentManager $manager
     */
    public function __construct(DocumentManager $manager, RouterInterface $router)
    {
        $this->manager = $manager ;
        $this->router = $router;
    }

    /**
     * {@inheritDocs}
     */
    public function generateRoutes()
    {
        $routeInformation = array();

        $contentDocuments = $this->manager->createQuery(
            "SELECT * FROM [nt:unstructured] WHERE (visible = true)",
            QueryInterface::JCR_SQL2
        )->execute();

        foreach ($contentDocuments as $document) {
            try {
                $urlInformation = new UrlInformation();
                $urlInformation->setLoc($this->router->generate($document, array(), true));

                $routeInformation[] = $urlInformation;
            } catch (\Exception $e) {

            }
        }

        return $routeInformation;
    }
}
