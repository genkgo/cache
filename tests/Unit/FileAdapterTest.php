<?php
namespace Genkgo\Cache\Unit;

use Genkgo\Cache\AbstractTestCase;
use Genkgo\Cache\Adapter\FileAdapter;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class ArrayAdapterTest
 * @package Genkgo\Cache\Unit
 */
class FileAdapterTest extends AbstractTestCase
{
    private static $dir;

    public static function setUpBeforeClass()
    {
        $dir = sys_get_temp_dir() . '/cache';
        if (file_exists($dir) === false) {
            mkdir($dir);
        }
        self::$dir = $dir;
    }

    public static function tearDownAfterClass()
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(self::$dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $info) {
            $todo = ($info->isDir() ? 'rmdir' : 'unlink');
            $todo($info->getRealPath());
        }

        rmdir(self::$dir);
    }

    /**
     *
     */
    public function testEmpty()
    {
        $cache = new FileAdapter(self::$dir);
        $this->assertNull($cache->get('item'));
    }

    /**
     *
     */
    public function testGetSet()
    {
        $cache = new FileAdapter(self::$dir);
        $cache->set('item', 'content');
        $this->assertEquals('content', $cache->get('item'));
    }

    /**
     *
     */
    public function testDelete()
    {
        $cache = new FileAdapter(self::$dir);
        $cache->set('item', 'content');
        $this->assertEquals('content', $cache->get('item'));
        $cache->delete('item');
        $this->assertNull($cache->get('item'));
    }

    /**
     *
     */
    public function testChmod()
    {
        $cache = new FileAdapter(self::$dir, 0777);
        $cache->set('item', 'content');

        $perms = fileperms(self::$dir . '/' . md5('item'));
        $this->assertEquals('0777', substr(sprintf('%o', $perms), -4));
    }

    /**
     *
     */
    public function testDeleteAll()
    {
        $cache = new FileAdapter(self::$dir, 0777);
        $cache->set('item', 'content');
        $this->assertTrue(file_exists(self::$dir . '/' . md5('item')));

        $cache->delete('*');
        $this->assertFalse(file_exists(self::$dir . '/' . md5('item')));
    }

    /**
     *
     */
    public function testNamespaceSlash()
    {
        if (file_exists(self::$dir.'/namespace')) {
            rmdir(self::$dir.'/namespace');
        }

        $cache = new FileAdapter(self::$dir, 0777, '/');
        $cache->set('namespace/item', 'content');
        $this->assertTrue(file_exists(self::$dir . '/89801e9e98979062e84647433a8ed3e9/' . md5('item')));

        $this->assertEquals('content', file_get_contents(self::$dir . '/89801e9e98979062e84647433a8ed3e9/' . md5('item')));
        $this->assertEquals('content', $cache->get('namespace/item'));

        $cache->delete('namespace/*');
        $this->assertFalse(file_exists(self::$dir . '/89801e9e98979062e84647433a8ed3e9/' . md5('item')));
    }

    /**
     *
     */
    public function testNamespaceOther()
    {
        if (file_exists(self::$dir.'/namespace')) {
            rmdir(self::$dir.'/namespace');
        }

        $cache = new FileAdapter(self::$dir, 0777, ':');
        $cache->set('namespace:item/sub', 'content');
        $this->assertTrue(file_exists(self::$dir . '/89801e9e98979062e84647433a8ed3e9/' . md5('item/sub')));

        $this->assertEquals('content', file_get_contents(self::$dir . '/89801e9e98979062e84647433a8ed3e9/' . md5('item/sub')));
        $this->assertEquals('content', $cache->get('namespace:item/sub'));

        $cache->delete('namespace:*');
        $this->assertFalse(file_exists(self::$dir . '/89801e9e98979062e84647433a8ed3e9/' . md5('item/sub')));
    }

    /**
     *
     */
    public function testTtl()
    {
        $cache = new FileAdapter(self::$dir, 0777, null, 1);
        $cache->set('item', 'content');
        $this->assertEquals('content', $cache->get('item'));

        sleep(1.1);
        $this->assertNull($cache->get('item'));
    }
}
