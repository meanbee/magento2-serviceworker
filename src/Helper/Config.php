<?php

namespace Meanbee\ServiceWorker\Helper;

use Magento\Store\Model\ScopeInterface;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ENABLE = "web/serviceworker/enable";
    const XML_PATH_OFFLINE_PAGE = "web/serviceworker/offline_page";
    const XML_PATH_URL_BLACKLIST = "web/serviceworker/url_blacklist";
    const XML_PATH_GA_OFFLINE_ENABLE = "web/serviceworker/ga_offline_enable";

    const PATH_WILDCARD_SYMBOL = "*";

    const SERVICEWORKER_ENDPOINT = "serviceworker.js";

    /** @var \Magento\Cms\Helper\Page $cmsPageHelper */
    protected $cmsPageHelper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Cms\Helper\Page $cmsPageHelper
    ) {
        parent::__construct($context);

        $this->cmsPageHelper = $cmsPageHelper;
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
     * Get the list of URLs blacklisted from cache.
     *
     * @return array
     */
    public function getUrlBlacklist()
    {
        $base_url = $this->_urlBuilder->getBaseUrl();

        $paths = array_filter(array_map(
            "trim",
            explode("\n", $this->scopeConfig->getValue(static::XML_PATH_URL_BLACKLIST, ScopeInterface::SCOPE_STORE))
        ));

        $data = [];

        foreach ($paths as $path) {
            $data[] = [
                "path"     => $base_url . $path,
                "strategy" => "networkOnly",
            ];
        }

        return $data;
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
