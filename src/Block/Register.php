<?php

namespace Meanbee\ServiceWorker\Block;

use Meanbee\ServiceWorker\Helper\Config;

class Register extends \Magento\Framework\View\Element\Template
{
    /** @var Config $config */
    protected $config;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
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
