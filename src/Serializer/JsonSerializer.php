<?php
namespace Genkgo\Cache\Serializer;

use Genkgo\Cache\SerializerInterface;

final class JsonSerializer implements SerializerInterface {

    /**
     * @var bool
     */
    private $decodeAssoc;

    /**
     * @param bool $decodeAssoc
     */
    public function __construct($decodeAssoc = true)
    {
        $this->decodeAssoc = $decodeAssoc;
    }

    /**
     * @param $item
     * @return mixed
     */
    public function serialize($item)
    {
        return json_encode($item);
    }

    /**
     * @param $item
     * @return mixed
     */
    public function deserialize($item)
    {
        return json_decode($item, $this->decodeAssoc);
    }
}