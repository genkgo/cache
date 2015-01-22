<?php
namespace Genkgo\Cache;

/**
 * Interface CacheAdapterInterface
 * @package Genkgo\Cache
 */
interface CacheAdapterInterface
{
    /**
     * Gets a cache entry
     * returning null if not in cache
     *
     * @param $key
     * @return null|mixed
     */
    public function get($key);

    /**
     * Sets a cache entry
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function set($key, $value);

    /**
     * Deletes a cache entry
     *
     * @param $key
     * @return void
     */
    public function delete($key);
}
