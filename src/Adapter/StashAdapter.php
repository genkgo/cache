<?php
namespace Genkgo\Cache\Adapter;

use Genkgo\Cache\CacheAdapterInterface;
use Stash\Pool;

/**
 * Class StashAdapter
 * @package Genkgo\Cache\Adapter\Stash
 */
class StashAdapter implements CacheAdapterInterface
{
    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var null
     */
    private $expire;

    /**
     * @param Pool $pool
     * @param null $expire
     */
    public function __construct(Pool $pool, $expire = null)
    {
        $this->pool = $pool;
        $this->expire = $expire;
    }

    /**
     * Gets a cache entry
     * returning null if not in cache
     *
     * @param $key
     * @return null|mixed
     */
    public function get($key)
    {
        $item = $this->pool->getItem($key);
        if ($item->isMiss() === false) {
            return $item->get();
        } else {
            return null;
        }
    }

    /**
     * Sets a cache entry
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function set($key, $value)
    {
        $item = $this->pool->getItem($key);
        $item->set($value, $this->expire);
    }

    /**
     * Deletes a cache entry
     *
     * @param $key
     * @return void
     */
    public function delete($key)
    {
        $item = $this->pool->getItem($key);
        $item->clear();
    }
}
