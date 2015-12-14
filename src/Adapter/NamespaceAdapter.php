<?php
namespace Genkgo\Cache\Adapter;

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
     * @var string
     */
    private $separator;

    /**
     * @param CacheAdapterInterface $cache
     * @param $nsName
     * @param string $separator
     */
    public function __construct(CacheAdapterInterface $cache, $nsName, $separator = '.')
    {
        $this->cache = $cache;
        $this->nsName = $nsName;
        $this->separator = $separator;
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
        return $this->nsName . $this->separator . $key;
    }
}
