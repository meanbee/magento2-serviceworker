<?php

namespace Meanbee\ServiceWorker\Block;

use Magento\Framework\Json\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Meanbee\ServiceWorker\Helper\Config;

/**
 * @SuppressWarnings(PHPMD.ShortClassName)
 */
class Js extends Template
{
    public const VERSION_PREFIX = "mbsw";

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Data
     */
    protected $jsonHelper;

    /**
     * Construct.
     *
     * @param Context   $context
     * @param Config    $config
     * @param Data      $jsonHelper
     * @param array     $data
     */
    public function __construct(
        Context $context,
        Config $config,
        Data $jsonHelper,
        array $data
    ) {
        $this->config = $config;

        $this->jsonHelper = $jsonHelper;

        $data["cache_lifetime"] = 60 * 60 * 24 * 365;

        parent::__construct($context, $data);
    }

    /**
     * Get the provided data encoded as a JSON object.
     *
     * @param mixed $data
     *
     * @return string
     */
    public function jsonEncode($data)
    {
        return $this->jsonHelper->jsonEncode($data);
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
     * Get Url Service Worker Google Analytics.
     *
     * @return string|null
     */
    public function getUrlServiceWorkerGA()
    {
        if ($this->isGaOfflineEnabled()) {
            return $this->getViewFileUrl("Meanbee_ServiceWorker::js/lib/workbox-google-analytics.prod.v1.0.0.js");
        }
        return null;
    }

    /**
     * Get Url Service Worker Js.
     *
     * @return string
     */
    public function getUrlServiceWorkerJs()
    {
        return $this->getViewFileUrl("Meanbee_ServiceWorker::js/lib/workbox-sw.prod.v1.0.1.js");
    }

    /**
     * Get the path prefix for backend requests.
     *
     * @return string
     */
    public function getBackendPathPrefix()
    {
        return $this->config->getBackendPathPrefix();
    }

    /**
     * Get the configured paths with custom caching strategies.
     *
     * @return \array[]
     */
    public function getCustomStrategies()
    {
        return $this->config->getCustomStrategies();
    }

    /**
     * Check if Offline Google Analytics features are enabled.
     *
     * @return bool
     */
    public function isGaOfflineEnabled()
    {
        return $this->config->isGaOfflineEnabled();
    }
}
