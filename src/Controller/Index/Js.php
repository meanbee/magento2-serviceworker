<?php

namespace Meanbee\ServiceWorker\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\LayoutFactory;

/**
 * @SuppressWarnings(PHPMD.ShortClassName)
 */
class Js extends Action
{
    /**
     * @var LayoutFactory
     */
    protected $layoutFactory;

    /**
     * Index constructor.
     *
     * @param Context       $context
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        Context $context,
        LayoutFactory $layoutFactory
    ) {
        $this->layoutFactory = $layoutFactory;
        parent::__construct($context);
    }

    /**
     * Execute.
     *
     * @return LayoutFactory
     */
    public function execute()
    {
        return $this->layoutFactory->create()
            ->addDefaultHandle()
            ->setHeader("Content-Type", "text/javascript", true);
    }
}
