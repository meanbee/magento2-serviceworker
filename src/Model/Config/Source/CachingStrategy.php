<?php

namespace Meanbee\ServiceWorker\Model\Config\Source;

class CachingStrategy implements \Magento\Framework\Option\ArrayInterface
{
    const CACHE_FIRST = "cacheFirst";
    const NETWORK_FIRST = "networkFirst";
    const NETWORK_ONLY = "networkOnly";

    /**
     * Get options in "key-value" format
     *
     * @return string[]
     */
    public function toArray()
    {
        return [
            static::CACHE_FIRST   => __("Cache First"),
            static::NETWORK_FIRST => __("Network First"),
            static::NETWORK_ONLY  => __("Network Only"),
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
