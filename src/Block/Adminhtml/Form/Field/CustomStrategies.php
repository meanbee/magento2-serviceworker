<?php

namespace Meanbee\ServiceWorker\Block\Adminhtml\Form\Field;

use Magento\Framework\DataObject;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Meanbee\ServiceWorker\Block\Adminhtml\Form\Field\CachingStrategy;

class CustomStrategies extends AbstractFieldArray
{
    /**
     * @var CachingStrategy
     */
    protected $strategyRenderer;

    /**
     * Prepare to render.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
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
     * Prepare Array Row.
     *
     * @param DataObject $row
     *
     * @return void
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _prepareArrayRow(DataObject $row)
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
                CachingStrategy::class,
                "",
                ["data" => [
                    "is_render_to_js_template" => true,
                ]]
            );
        }

        return $this->strategyRenderer;
    }
}
