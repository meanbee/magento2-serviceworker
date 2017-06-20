<?php

namespace Meanbee\ServiceWorker\Block\Adminhtml\Form\Field;

use \Magento\Framework\View\Element;

class CachingStrategy extends Element\Html\Select
{

    public function __construct(
        Element\Context $context,
        \Meanbee\ServiceWorker\Model\Config\Source\CachingStrategy $optionsProvider,
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
