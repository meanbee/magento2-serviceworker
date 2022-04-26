<?php

namespace Meanbee\ServiceWorker\Controller;

use Meanbee\ServiceWorker\Helper\Config;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\RequestInterface;

class Router implements RouterInterface
{

    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * Construct.
     *
     * @param ActionFactory $actionFactory
     */
    public function __construct(
        ActionFactory $actionFactory
    ) {
        $this->actionFactory = $actionFactory;
    }

    /**
     * Match.
     *
     * @param RequestInterface $request
     * @return Forward|null
     */
    public function match(RequestInterface $request)
    {
        if (trim($request->getPathInfo(), "/") == Config::SERVICEWORKER_ENDPOINT) {
            $request
                ->setModuleName("serviceworker")
                ->setControllerName("index")
                ->setActionName("js");
            
            return $this->actionFactory->create(Forward::class);
        }

        return null;
    }
}
