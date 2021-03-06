<?php
namespace Genkgo\Cache\Adapter;

use Genkgo\Cache\CacheAdapterInterface;
use Genkgo\Cache\CallbackCacheInterface;

/**
 * Class SimpleCallbackAdapter
 * @package Genkgo\Cache
 */
class SimpleCallbackAdapter implements CallbackCacheInterface
{
    /**
     * @var CacheAdapterInterface
     */
    private $cache;

    /**
     * @param CacheAdapterInterface $cache
     */
    public function __construct(CacheAdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param $key
     * @param callable $cb
     * @return mixed|null
     */
    public function get($key, callable $cb)
    {
        $item = $this->cache->get($key);
        if ($item === null) {
            $item = $cb();
            $this->cache->set($key, $item);
            return $item;
        } else {
            return $item;
        }
    }
}
