<?php

namespace Meanbee\ServiceWorker\Block;

class Js extends \Magento\Framework\View\Element\Template
{
    const VERSION_PREFIX = "mbsw";

    /** @var \Meanbee\ServiceWorker\Helper\Config $config */
    protected $config;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Meanbee\ServiceWorker\Helper\Config $config,
        array $data
    ) {
        $this->config = $config;

        $data["cache_lifetime"] = 60 * 60 * 24 * 365;

        parent::__construct($context, $data);
    }

    /**
     * Get the service worker version string.
     *
     * @return string
     */
    public function getVersion()
    {
        return implode("-", [
            static::VERSION_PREFIX,
            time()
        ]);
    }

    /**
     * Get the offline notification page URL.
     *
     * @return string
     */
    public function getOfflinePageUrl()
    {
        return $this->config->getOfflinePageUrl();
    }

    /**
     * Get the list of URLs unavailable for caching or viewing offline.
     *
     * @return array
     */
    public function getUrlBlacklist()
    {
        return $this->config->getUrlBlacklist();
    }
}
