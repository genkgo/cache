<?php
namespace Genkgo\Cache\Serializer;

use Genkgo\Cache\SerializerInterface;

final class PhpSerializer implements SerializerInterface {

    /**
     * @param $item
     * @return mixed
     */
    public function serialize($item)
    {
        return serialize($item);
    }

    /**
     * @param $item
     * @return mixed
     */
    public function deserialize($item)
    {
        return unserialize($item);
    }
}