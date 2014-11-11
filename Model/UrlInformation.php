<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class UrlInformation
{
    /**
     * @var string
     */
    private $loc;

    /**
     * @var \DateTime
     */
    private $lastmod;

    /**
     * @var string One of the official/allowed.
     */
    private $changeFreq;

    /**
     * @var float
     */
    private $priority;

    /**
     * @var array
     */
    private $allowedChangeFreqs = array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never');

    /**
     * @var string $label As a string to display the route i.e. in html views.
     */
    private $label;

    /**
     * @var array|AlternateLocale[]
     */
    private $alternateLocales;

    public function __construct()
    {
        $this->alternateLocales = array();
    }

    public function toArray()
    {
        $result = array(
            'loc'               => $this->loc,
            'label'             => $this->label,
            'changefreq'        => $this->changeFreq,
            'lastmod'           => $this->lastmod,
            'priority'          => $this->priority,
            'alternate_locales' => array()
        );

        foreach ($this->alternateLocales as $locale) {
            $result['alternate_locales'][] = array('href' => $locale->href, 'href_locale' => $locale->hrefLocale);
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getChangeFreq()
    {
        return $this->changeFreq;
    }

    /**
     * @param string $changeFreq One of the official/allowed ones.
     *
     * @return $this
     */
    public function setChangeFreq($changeFreq)
    {
        if (!in_array($changeFreq, $this->allowedChangeFreqs)) {
            return $this;
        }

        $this->changeFreq = $changeFreq;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastmod()
    {
        return $this->lastmod;
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return $this
     */
    public function setLastmod(\DateTime $dateTime)
    {
        $lastmod = $dateTime->format('c');
        $this->lastmod = $lastmod;

        return $this;
    }

    /**
     * @return string
     */
    public function getLoc()
    {
        return $this->loc;
    }

    /**
     * @param string $loc
     *
     * @return $this
     */
    public function setLoc($loc)
    {
        $this->loc = $loc;

        return $this;
    }

    /**
     * @return float
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param float $priority
     *
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return array|AlternateLocale[]
     */
    public function getAlternateLocales()
    {
        return $this->alternateLocales;
    }

    /**
     * @param array|AlternateLocale[] $alternateLocales
     */
    public function setAlternateLocales($alternateLocales)
    {
        $this->alternateLocales = $alternateLocales;
    }

    /**
     * @param AlternateLocale $alternateLocale
     */
    public function addAlternateLocale(AlternateLocale $alternateLocale)
    {
        $this->alternateLocales[] = $alternateLocale;
    }
}
