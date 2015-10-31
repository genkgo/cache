<?php
namespace Genkgo\Cache\Adapters;

use Genkgo\Cache\CacheAdapterInterface;

/**
 * Class FileAdapter
 * @package Genkgo\Cache\Adapters
 */
class FileAdapter implements CacheAdapterInterface
{
    /**
     * @var string
     */
    private $directory;
    /**
     * @var null|int
     */
    private $chmod;

    /**
     * @param $directory
     * @param null $chmod
     */
    public function __construct($directory, $chmod = null)
    {
        $this->directory = $directory;
        $this->chmod = $chmod;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $file = $this->getFilename($key);
        file_put_contents($file, $value);

        if ($this->chmod !== null) {
            chmod($file, $this->chmod);
        }
    }

    /**
     * @param $key
     * @return null|mixed
     */
    public function get($key)
    {
        $file = $this->getFilename($key);
        if ($this->exists($file)) {
            return file_get_contents($file);
        }
        return null;
    }

    /**
     * @param $key
     */
    public function delete($key)
    {
        if (strpos($key, '*') !== false) {
            $list = new \GlobIterator($this->directory . '/' . $key);

            foreach ($list as $cacheItem) {
                unlink($cacheItem->getPathName());
            }

            return;
        }

        $file = $this->getFilename($key);
        if ($this->exists($file)) {
            unlink($file);
        }
    }

    /**
     * @param $file
     * @return bool
     */
    private function exists($file)
    {
        return file_exists($file);
    }

    /**
     * @param $key
     * @return string
     */
    private function getFilename($key)
    {
        return $this->directory . '/' . md5($key);
    }
}
