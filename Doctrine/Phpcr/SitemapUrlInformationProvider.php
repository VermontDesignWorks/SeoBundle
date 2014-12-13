<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Query\QueryInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishWorkflowChecker;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProviderInterface;
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
     * @var PublishWorkflowChecker
     */
    private $publishWorkflowChecker;

    /**
     * @param DocumentManager $manager
     * @param RouterInterface $router
     * @param PublishWorkflowChecker $publishWorkflowChecker
     */
    public function __construct(
        DocumentManager $manager,
        RouterInterface $router,
        PublishWorkflowChecker $publishWorkflowChecker
    ) {
        $this->manager = $manager ;
        $this->router = $router;
        $this->publishWorkflowChecker = $publishWorkflowChecker;
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
            if (!$this->publishWorkflowChecker->isGranted(array(PublishWorkflowChecker::VIEW_ATTRIBUTE), $document)) {
                continue;
            }
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
