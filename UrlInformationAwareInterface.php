<?php

namespace Symfony\Cmf\Bundle\SeoBundle;

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;

/**
 * Implementing that interface give the chance to activate a content for the sitemap
 * view and add some more information.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface UrlInformationAwareInterface
{
    /**
     * @return UrlInformation
     */
    public function getUrlInformation();
}
