<?php
namespace Genkgo\Cache\Adapters;

use Exception;
use Genkgo\Cache\CacheAdapterInterface;
use Genkgo\Cache\CallbackCacheInterface;

/**
 * Class StampedeCallbackAdapter
 * @package Genkgo\Cache
 */
class StampedeCallbackAdapter implements CallbackCacheInterface
{
    /**
     * @var CacheAdapterInterface
     */
    private $cache;

    private $pregenerateIn;

    private $useInvalidDataOnException = false;

    /**
     * @param CacheAdapterInterface $cache
     */
    public function __construct(CacheAdapterInterface $cache, $pregenerateInSeconds)
    {
        $this->cache = $cache;
        $this->pregenerateIn  = $pregenerateInSeconds;
    }

    /**
     * @param $key
     * @param callable $cb
     * @return mixed|null
     * @throws Exception
     */
    public function get($key, callable $cb)
    {
        $currentItem = $this->cache->get($key);
        if ($currentItem === null || $this->needsPregeneration($key) === true) {
            $this->lock($key);
            try {
                $item = $cb();
            } catch (Exception $e) {
                if ($this->useInvalidDataOnException) {
                    $item = $currentItem;
                } else {
                    $this->unlock($key);
                    throw $e;
                }
            }
            $this->cache->set($key, $item);
            $this->unlock($key);
            return $item;
        } else {
            return $currentItem;
        }
    }

    private function needsPregeneration ($key)
    {
        $regenerateOn = $this->cache->get('sp' . $key);
        if ($regenerateOn === null) {
            return true;
        }

        if ($regenerateOn === 'locked') {
            return false;
        }

        $regenerateOn = \DateTimeImmutable::createFromFormat(\DateTime::ISO8601, $regenerateOn);
        return ($regenerateOn <= new \DateTimeImmutable('now'));
    }

    private function lock ($key)
    {
        $this->cache->set('sp' . $key, 'locked');
    }

    private function unlock ($key)
    {
        $interval = 'PT' . $this->pregenerateIn . 'S';
        $this->cache->set('sp' . $key, (new \DateTimeImmutable('now'))->add(new \DateInterval($interval)));
    }
}
