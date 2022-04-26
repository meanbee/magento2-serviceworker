<?php

namespace Meanbee\ServiceWorker\Helper;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\ScopeInterface;
use Magento\Cms\Helper\Page;

class Config extends AbstractHelper
{
    public const XML_PATH_ENABLE = "web/serviceworker/enable";
    public const XML_PATH_OFFLINE_PAGE = "web/serviceworker/offline_page";
    public const XML_PATH_CUSTOM_STRATEGIES = "web/serviceworker/custom_strategies";
    public const XML_PATH_GA_OFFLINE_ENABLE = "web/serviceworker/ga_offline_enable";
    public const PATH_WILDCARD_SYMBOL = "*";
    public const SERVICEWORKER_ENDPOINT = "serviceworker.js";

    /**
     * @var Page
     */
    protected $cmsPageHelper;

    /**
     * @var DeploymentConfig
     */
    protected $deploymentConfig;

    /**
     * @var Json
     */
    protected $serializer;

    /**
     * Construct.
     *
     * @param Context $context
     * @param Page $cmsPageHelper
     * @param DeploymentConfig $deploymentConfig
     * @param Json $serializer
     */
    public function __construct(
        Context $context,
        Page $cmsPageHelper,
        DeploymentConfig $deploymentConfig,
        Json $serializer
    ) {
        parent::__construct($context);
        $this->cmsPageHelper = $cmsPageHelper;
        $this->deploymentConfig = $deploymentConfig;
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
        $identifier = $this->scopeConfig->getValue(static::XML_PATH_OFFLINE_PAGE, ScopeInterface::SCOPE_STORE);
        if ($identifier) {
            return $this->cmsPageHelper->getPageUrl($identifier);
        }
    }

    /**
     * Get the prefix path for backend requests.
     *
     * @return string
     */
    public function getBackendPathPrefix()
    {
        return $this->_urlBuilder->getBaseUrl()
            . $this->deploymentConfig->get(\Magento\Backend\Setup\ConfigOptionsList::CONFIG_PATH_BACKEND_FRONTNAME)
            . "/*";
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
        $customStrategies = $this->scopeConfig->getValue(
            static::XML_PATH_CUSTOM_STRATEGIES,
            ScopeInterface::SCOPE_STORE,
            $store
        );

        if (is_string($customStrategies) && !empty($customStrategies)) {
            $customStrategies = $this->serializer->unserialize($customStrategies);
        }

        if (!is_array($customStrategies)) {
            return [];
        }

        $baseUrl = $this->_urlBuilder->getBaseUrl(["_scope" => $store]);

        array_walk($customStrategies, function (&$item) use ($baseUrl) {
            $item["path"] = $baseUrl . $item["path"];
        });

        // Reset indexes to allow encoding as JSON array
        $customStrategies = array_values($customStrategies);

        return $customStrategies;
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
