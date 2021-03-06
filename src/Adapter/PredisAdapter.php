<?php
namespace Genkgo\Cache\Adapter;

use Genkgo\Cache\CacheAdapterInterface;
use Genkgo\Cache\SerializerInterface;
use Predis\ClientInterface;

/**
 * Class PredisAdapter
 * @package Genkgo\Cache\Adapter
 */
class PredisAdapter implements CacheAdapterInterface
{
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var int|null
     */
    private $ttl;

    /**
     * @param ClientInterface $client
     * @param SerializerInterface $serializer
     * @param null $ttl
     */
    public function __construct(
        ClientInterface $client,
        SerializerInterface $serializer,
        $ttl = null
    ) {
        $this->client = $client;
        $this->serializer = $serializer;
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
        return $this->serializer->deserialize($this->client->get($key));
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
        if ($this->ttl === null) {
            $this->client->set($key, $this->serializer->serialize($value));
        } else {
            $this->client->set($key, $this->serializer->serialize($value), 'ex', $this->ttl);
        }
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
            $this->deleteGlob($key);
        } else {
            $this->client->del([$key]);
        }
    }

    private function deleteGlob ($pattern) {
        $keys = $this->client->keys($pattern);
        $options = $this->client->getOptions();
        if (isset($options->prefix)) {
            $length = strlen($options->prefix->getPrefix());

            $keys = array_map(function ($key) use ($length) {
                return substr($key, $length);
            }, $keys);
        }

        if (count($keys) === 0) {
            return;
        }

        $this->client->del($keys);
    }
}
