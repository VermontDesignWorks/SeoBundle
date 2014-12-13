<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Doctrine\ODM\PHPCR\DocumentManager;
use Jackalope\Transport\Logging\LoggerInterface;
use PHPCR\Query\QueryInterface;
use Psr\Log\LoggerInterface as PsrLogger;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishWorkflowChecker;
use Symfony\Cmf\Bundle\SeoBundle\AlternateLocaleProviderInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtractorInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProviderInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

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
     * @var SecurityContextInterface
     */
    private $publishWorkflowChecker;
    /**
     * @var string
     */
    private $defaultChanFrequency;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param DocumentManager $manager
     * @param RouterInterface $router
     * @param SecurityContextInterface $publishWorkflowChecker
     * @param string $defaultChanFrequency
     * @param PsrLogger $logger
     */
    public function __construct(
        DocumentManager $manager,
        RouterInterface $router,
        SecurityContextInterface $publishWorkflowChecker,
        $defaultChanFrequency,
        PsrLogger $logger
    ) {
        $this->manager = $manager ;
        $this->router = $router;
        $this->publishWorkflowChecker = $publishWorkflowChecker;
        $this->defaultChanFrequency = $defaultChanFrequency;
        $this->logger = $logger;
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
                $this->logger->info($e->getMessage());
            }
        }

        return $routeInformation;
    }

    /**
     * To generate the url information object out of the content this method
     * extracts the data from the given content.
     *
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
