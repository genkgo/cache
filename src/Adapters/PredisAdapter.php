<?php
namespace Genkgo\Cache\Adapters;

use Genkgo\Cache\CacheAdapterInterface;
use Predis\ClientInterface;

/**
 * Class PredisAdapter
 * @package Genkgo\Cache\Adapters
 */
class PredisAdapter implements CacheAdapterInterface
{
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var
     */
    private $ttl;

    /**
     * @param ClientInterface $client
     * @param null $ttl
     */
    public function __construct(ClientInterface $client, $ttl = null)
    {
        $this->client = $client;
        $this->ttl = $ttl;
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
        return $this->client->get($key);
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
        $this->client->set($key, $value, $this->ttl);
    }

    /**
     * Deletes a cache entry
     *
     * @param $key
     * @return void
     */
    public function delete($key)
    {
        if (strpos($key, '*') !== false) {
            $key = $this->client->keys('*');
            $options = $this->client->getOptions();
            if (isset($options->prefix)) {
                $length = strlen($options->prefix->getPrefix());

                $key = array_map(function ($key) use ($length) {
                    return substr($key, $length);
                }, $key);
            }
        } else {
            $key = [$key];
        }

        $this->client->del($key);
    }
}
