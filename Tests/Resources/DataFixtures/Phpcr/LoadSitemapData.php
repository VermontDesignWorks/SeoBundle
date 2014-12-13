<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PHPCR\Util\NodeHelper;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\AlternateLocaleContent;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\SeoAwareContent;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\ContentWithExtractors;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\SitemapAwareContent;

class LoadSitemapData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        NodeHelper::createPath($manager->getPhpcrSession(), '/test');

        NodeHelper::createPath($manager->getPhpcrSession(), '/test/content');
        NodeHelper::createPath($manager->getPhpcrSession(), '/test/routes');

        $contentRoot = $manager->find(null, '/test/content');
        $routeRoot = $manager->find(null, '/test/routes');

        $sitemapAwareContent = new SitemapAwareContent();
        $sitemapAwareContent
            ->setIsVisibleForSitemap(true)
            ->setTitle('Sitemap Aware Content')
            ->setName('sitemap-aware')
            ->setParentDocument($contentRoot)
            ->setBody('Content for that is sitemap aware');

        $manager->persist($sitemapAwareContent);

        $route = new Route();
        $route->setPosition($routeRoot, 'sitemap-aware');
        $route->setContent($sitemapAwareContent);

        $manager->persist($route);

        $manager->flush();

    }
}
