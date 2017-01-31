<?php

namespace Meanbee\ServiceWorker\Controller;

use Meanbee\ServiceWorker\Helper\Config;

class Router implements \Magento\Framework\App\RouterInterface
{

    /** @var \Magento\Framework\App\ActionFactory $actionFactory */
    protected $actionFactory;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory
    ) {
        $this->actionFactory = $actionFactory;
    }

    /**
     * @inheritdoc
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        if (trim($request->getPathInfo(), "/") == Config::SERVICEWORKER_ENDPOINT) {
            $request
                ->setModuleName("serviceworker")
                ->setControllerName("index")
                ->setActionName("js");

            return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
        }

        return null;
    }
}
