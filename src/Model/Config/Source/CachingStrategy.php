<?php

namespace Meanbee\ServiceWorker\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class CachingStrategy implements ArrayInterface
{
    public const CACHE_FIRST = "cacheFirst";
    public const NETWORK_FIRST = "networkFirst";
    public const NETWORK_ONLY = "networkOnly";

    /**
     * Get options in "key-value" format
     *
     * @return array
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
     * To Option Array.
     *
     * @return array
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
