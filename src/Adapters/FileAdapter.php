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
     * @var null|string
     */
    private $directorySeparator;
    /**
     * @var null|int
     */
    private $ttl;

    /**
     * @param $directory
     * @param null $chmod
     * @param null $directorySeparator
     * @param null $ttl
     */
    public function __construct(
        $directory,
        $chmod = null,
        $directorySeparator = null,
        $ttl = null
    ) {
        $this->directory = $directory;
        $this->chmod = $chmod;
        $this->directorySeparator = $directorySeparator;
        $this->ttl = $ttl;
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
        if ($this->exists($file) && $this->valid($file)) {
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
            $this->deleteGlob($key);
            return;
        }

        $file = $this->getFilename($key);
        if ($this->exists($file)) {
            unlink($file);
        }
    }

    /**
     * @param $pattern
     */
    private function deleteGlob ($pattern) {
        list($directory, $file) = $this->getDirectoryAndFile($pattern);
        $list = new \GlobIterator($directory . '/' . $file);

        foreach ($list as $cacheItem) {
            if ($cacheItem->isDir()) {
                continue;
            }
            unlink($cacheItem->getPathName());
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
     * @param $file
     * @return bool
     */
    private function valid ($file) {
        return $this->ttl === null || (filemtime($file) + $this->ttl > time());
    }

    /**
     * @param $key
     * @return string
     */
    private function getFilename($key)
    {
        list($directory, $file) = $this->getDirectoryAndFile($key);
        return $directory . '/' . md5($file);
    }

    /**
     * @param $key
     * @return array
     */
    private function getDirectoryAndFile($key)
    {
        $directory = $this->directory;

        if ($this->directorySeparator !== null) {
            while (($position = strpos($key, $this->directorySeparator)) !== false) {
                $dirName = md5(substr($key, 0, $position));
                $directory = $directory . '/' . $dirName;
                $this->createSubDirectoryIfNotExists($directory);
                $key = substr($key, $position + 1);
            }
        }

        return [$directory, $key];
    }

    /**
     * @param $directory
     */
    private function createSubDirectoryIfNotExists($directory)
    {
        if (file_exists($directory) === false) {
            mkdir($directory);
            if ($this->chmod !== null) {
                chmod($directory, $this->chmod);
            }
        }
    }
}
