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
     * @param $directory
     */
    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $file = $this->getFilename($key);
        file_put_contents($file, $value);
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
