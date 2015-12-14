<?php
namespace Genkgo\Cache\Adapter;

use Genkgo\Cache\CacheAdapterInterface;

/**
 * Class ArrayAdapter
 * @package Genkgo\Cache\Adapter
 */
class ArrayAdapter implements CacheAdapterInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param $key
     * @return null|mixed
     */
    public function get($key)
    {
        if ($this->exists($key)) {
            return $this->data[$key];
        }
        return null;
    }

    /**
     * @param $key
     */
    public function delete($key)
    {
        unset($this->data[$key]);
    }

    /**
     * @param $key
     * @return bool
     */
    private function exists($key)
    {
        return isset($this->data[$key]) || array_key_exists($key, $this->data);
    }
}
