<?php
namespace Genkgo\Cache;

interface SerializerInterface {

    /**
     * @param $item
     * @return mixed
     */
    public function serialize($item);

    /**
     * @param $item
     * @return mixed
     */
    public function deserialize ($item);

}