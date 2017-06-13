<?php

namespace Meanbee\ServiceWorker\Model\Config\Source;

class CachingStrategy implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Get options in "key-value" format
     *
     * @return string[]
     */
    public function toArray()
    {
        return [
            "cacheFirst"   => __("Cache First"),
            "networkFirst" => __("Network First"),
            "networkOnly"  => __("Network Only"),
        ];
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $options = $this->toArray();

        array_walk($options, function (&$value, $key) {
            $value = [
                "value" => $key,
                "label" => $value,
            ];
        });

        return $options;
    }
}
