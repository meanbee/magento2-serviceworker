<?php

namespace Meanbee\ServiceWorker\Helper;

use Magento\Store\Model\ScopeInterface;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ENABLE = "web/serviceworker/enable";
    const XML_PATH_OFFLINE_PAGE = "web/serviceworker/offline_page";
    const XML_PATH_CUSTOM_STRATEGIES = "web/serviceworker/custom_strategies";
    const XML_PATH_GA_OFFLINE_ENABLE = "web/serviceworker/ga_offline_enable";

    const PATH_WILDCARD_SYMBOL = "*";

    const SERVICEWORKER_ENDPOINT = "serviceworker.js";

    /** @var \Magento\Cms\Helper\Page $cmsPageHelper */
    protected $cmsPageHelper;

    /** @var \Magento\Framework\Serialize\Serializer\Json $serializer */
    protected $serializer;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Cms\Helper\Page $cmsPageHelper,
        \Magento\Framework\Serialize\Serializer\Json $serializer
    ) {
        parent::__construct($context);

        $this->cmsPageHelper = $cmsPageHelper;
        $this->serializer = $serializer;
    }

    /**
     * Check if the service worker is enabled on the given store.
     *
     * @param string $store
     *
     * @return bool
     */
    public function isEnabled($store = null)
    {
        return $this->scopeConfig->isSetFlag(static::XML_PATH_ENABLE, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Get the URL for the Offline Notification page.
     *
     * Warning: Always returns the URL for the current store scope due to Magento_Cms helper limitation.
     *
     * @return string
     */
    public function getOfflinePageUrl()
    {
        if ($identifier = $this->scopeConfig->getValue(static::XML_PATH_OFFLINE_PAGE, ScopeInterface::SCOPE_STORE)) {
            return $this->cmsPageHelper->getPageUrl($identifier);
        }
    }

    /**
     * Get the configured paths with custom caching strategies.
     *
     * @param string $store
     *
     * @return array[]
     */
    public function getCustomStrategies($store = null)
    {
        $custom_strategies = $this->scopeConfig->getValue(static::XML_PATH_CUSTOM_STRATEGIES, ScopeInterface::SCOPE_STORE, $store);

        if (is_string($custom_strategies) && !empty($custom_strategies)) {
            $custom_strategies = $this->serializer->unserialize($custom_strategies);
        }

        if (!is_array($custom_strategies)) {
            return [];
        }

        $base_url = $this->_urlBuilder->getBaseUrl(["_scope" => $store]);

        array_walk($custom_strategies, function (&$item) use ($base_url) {
            $item["path"] = $base_url . $item["path"];
        });

        // Reset indexes to allow encoding as JSON array
        $custom_strategies = array_values($custom_strategies);

        return $custom_strategies;
    }

    /**
     * Check if Offline Google Analytics features are enabled.
     *
     * @param string $store
     *
     * @return bool
     */
    public function isGaOfflineEnabled($store = null)
    {
        return $this->scopeConfig->isSetFlag(static::XML_PATH_GA_OFFLINE_ENABLE, ScopeInterface::SCOPE_STORE, $store);
    }
}
