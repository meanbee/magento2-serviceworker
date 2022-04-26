<?php

namespace Meanbee\ServiceWorker\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Meanbee\ServiceWorker\Helper\Config;

class Register extends Template
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Construct.
     *
     * @param Context   $context
     * @param Config    $config
     * @param array     $data
     */
    public function __construct(
        Context $context,
        Config $config,
        array $data
    ) {
        parent::__construct($context, $data);

        $this->config = $config;
    }

    /**
     * Check if the service worker is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->config->isEnabled();
    }

    /**
     * Get the service worker JS URL.
     *
     * @return string
     */
    public function getServiceWorkerJsUrl()
    {
        return $this->_urlBuilder->getDirectUrl(Config::SERVICEWORKER_ENDPOINT);
    }
}
