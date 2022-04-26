<?php

namespace Meanbee\ServiceWorker\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use Meanbee\ServiceWorker\Model\Config\Source\CachingStrategy as OptCachingStrategy;

class CachingStrategy extends Select
{

    /**
     * Construct.
     *
     * @param Context            $context
     * @param OptCachingStrategy $optionsProvider
     * @param array              $data
     */
    public function __construct(
        Context $context,
        OptCachingStrategy $optionsProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->setOptions($optionsProvider->toOptionArray());
    }

    /**
     * Set the input element name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setInputName($name)
    {
        return $this->setName($name);
    }
}
