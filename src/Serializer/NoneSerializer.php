<?php
namespace Genkgo\Cache\Serializer;

use Genkgo\Cache\SerializerInterface;

final class NoneSerializer implements SerializerInterface {

    /**
     * @param $item
     * @return mixed
     */
    public function serialize($item)
    {
        return $item;
    }

    /**
     * @param $item
     * @return mixed
     */
    public function deserialize($item)
    {
        return $item;
    }
}