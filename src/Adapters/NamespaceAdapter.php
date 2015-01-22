<?php
namespace Genkgo\Cache\Adapters;

use Genkgo\Cache\CacheAdapterInterface;

/**
 * Class NamespaceAdapter
 * @package Genkgo\Cache
 */
class NamespaceAdapter implements CacheAdapterInterface
{
    /**
     * @var CacheAdapterInterface
     */
    private $cache;
    /**
     * @var string
     */
    private $nsName;

    /**
     * @param CacheAdapterInterface $cache
     * @param $nsName
     */
    public function __construct(CacheAdapterInterface $cache, $nsName)
    {
        $this->cache = $cache;
        $this->nsName = $nsName;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->cache->get($this->getKey($key));
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->cache->set($this->getKey($key), $value);
    }

    /**
     * @param $key
     */
    public function delete($key)
    {
        $this->cache->delete($this->getKey($key));
    }

    /**
     * @param $key
     * @return string
     */
    private function getKey($key)
    {
        return $this->nsName . '.' . $key;
    }
}
