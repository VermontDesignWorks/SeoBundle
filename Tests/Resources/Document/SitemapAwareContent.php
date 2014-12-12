<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document;

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\UrlInformationAwareInterface;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapAwareContent extends ContentBase implements UrlInformationAwareInterface
{
    /**
     * @var UrlInformation
     *
     *
     */
    private $urlInformation;

    /**
     * @return UrlInformation
     */
    public function getUrlInformation()
    {
        return $this->urlInformation;
    }
}
