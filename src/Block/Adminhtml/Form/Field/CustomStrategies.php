<?php

namespace Meanbee\ServiceWorker\Block\Adminhtml\Form\Field;

class CustomStrategies extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /** @var CachingStrategy */
    protected $strategyRenderer;

    /**
     * @inheritdoc
     */
    protected function _prepareToRender()
    {
        $this->addColumn("path", [
            "label" => __("URL Path"),
        ]);

        $this->addColumn("strategy", [
            "label"    => __("Strategy"),
            "renderer" => $this->getStrategyRenderer(),
        ]);

        $this->_addAfter = false;
    }

    /**
     * @inheritdoc
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $optionName = sprintf("option_%s", $this->getStrategyRenderer()->calcOptionHash($row->getData("strategy")));

        $row->setData("option_extra_attrs", [
            $optionName => 'selected="selected"',
        ]);
    }

    /**
     * Get the caching strategy input field renderer.
     *
     * @return CachingStrategy
     */
    protected function getStrategyRenderer()
    {
        if (!$this->strategyRenderer) {
            $this->strategyRenderer = $this->getLayout()->createBlock(
                "Meanbee\\ServiceWorker\\Block\\Adminhtml\\Form\\Field\\CachingStrategy",
                "",
                ["data" => [
                    "is_render_to_js_template" => true,
                ]]
            );
        }

        return $this->strategyRenderer;
    }
}
