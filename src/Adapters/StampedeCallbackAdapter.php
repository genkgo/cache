<?php
namespace Genkgo\Cache\Adapters;

use DateInterval;
use DateTime;
use DateTimeImmutable;
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

    /**
     * @var int
     */
    private $pregenerateIn;

    /**
     * @var bool
     */
    private $useInvalidDataOnException = false;

    /**
     * @param CacheAdapterInterface $cache
     */
    public function __construct(CacheAdapterInterface $cache, $pregenerateInSeconds)
    {
        $this->cache = $cache;
        $this->pregenerateIn  = $pregenerateInSeconds;
    }

    public function useInvalidDateOnException ()
    {
        $this->useInvalidDataOnException = true;
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

    /**
     * @param $key
     * @return bool
     */
    private function needsPregeneration ($key)
    {
        $regenerateOn = $this->cache->get('sp' . $key);
        if ($regenerateOn === null) {
            return true;
        }

        if ($regenerateOn === 'locked') {
            return false;
        }

        $regenerateOn = DateTimeImmutable::createFromFormat(DateTime::ISO8601, $regenerateOn);
        return ($regenerateOn <= new DateTimeImmutable('now'));
    }

    /**
     * @param $key
     */
    private function lock ($key)
    {
        $this->cache->set('sp' . $key, 'locked');
    }

    /**
     * @param $key
     */
    private function unlock ($key)
    {
        $interval = new DateInterval('PT' . $this->pregenerateIn . 'S');
        $regeneratedOn = (new DateTimeImmutable('now'))->add($interval)->format(DateTime::ISO8601);
        $this->cache->set('sp' . $key, $regeneratedOn);
    }
}
