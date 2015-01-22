<?php
namespace Genkgo\Cache;

/**
 * Interface CallbackInterface
 * @package Genkgo\Cache
 */
interface CallbackCacheInterface
{
    /**
     * @param $key
     * @param callable $cb
     * @return mixed
     */
    public function get($key, callable $cb);
}
