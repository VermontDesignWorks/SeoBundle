<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Query\QueryInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishWorkflowChecker;
use Symfony\Cmf\Bundle\SeoBundle\AlternateLocaleProviderInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtractorInterface;
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
     * @var AlternateLocaleProviderInterface
     */
    protected $alternateLocaleProvider;

    /**
     * @var ExtractorInterface
     */
    protected $titleExtractor;

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
     * @var string
     */
    private $defaultChanFrequency;

    /**
     * @param DocumentManager        $manager
     * @param RouterInterface        $router
     * @param PublishWorkflowChecker $publishWorkflowChecker
     * @param string                 $defaultChanFrequency
     */
    public function __construct(
        DocumentManager $manager,
        RouterInterface $router,
        PublishWorkflowChecker $publishWorkflowChecker,
        $defaultChanFrequency
    ) {
        $this->manager = $manager ;
        $this->router = $router;
        $this->publishWorkflowChecker = $publishWorkflowChecker;
        $this->defaultChanFrequency = $defaultChanFrequency;
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
                $routeInformation[] = $this->computeUrlInformationFromContent($document);
            } catch (\Exception $e) {

            }
        }

        return $routeInformation;
    }

    /**
     * @param $content
     *
     * @return UrlInformation
     */
    protected function computeUrlInformationFromContent($content)
    {
        $urlInformation = new UrlInformation();
        $urlInformation->setLoc($this->router->generate($content, array(), true));
        $urlInformation->setChangeFreq($this->defaultChanFrequency);

        if ($this->alternateLocaleProvider) {
            $collection = $this->alternateLocaleProvider->createForContent($content);
            $urlInformation->setAlternateLocales($collection->toArray());
        }

        if (method_exists($content, 'getTitle')) {
            $urlInformation->setLabel($content->getTitle());
        }

        return $urlInformation;
    }

    /**
     * @param AlternateLocaleProviderInterface $alternateLocaleProvider
     */
    public function setAlternateLocaleProvider($alternateLocaleProvider)
    {
        $this->alternateLocaleProvider = $alternateLocaleProvider;
    }

    public function setTitleExtractor(ExtractorInterface $titleExtractor)
    {
        $this->titleExtractor = $titleExtractor;
    }
}
