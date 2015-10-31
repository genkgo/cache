<?php
namespace Genkgo\Cache\Unit;

use Genkgo\Cache\AbstractTestCase;
use Genkgo\Cache\Adapters\FileAdapter;

/**
 * Class ArrayAdapterTest
 * @package Genkgo\Cache\Unit
 */
class FileAdapterTest extends AbstractTestCase
{
    private $dir;

    protected function setUp()
    {
        $dir = sys_get_temp_dir() . '/cache';
        if (file_exists($dir) === false) {
            mkdir($dir);
        }
        $this->dir = $dir;
    }

    /**
     *
     */
    public function testEmpty()
    {
        $cache = new FileAdapter($this->dir);
        $this->assertNull($cache->get('item'));
    }

    /**
     *
     */
    public function testGetSet()
    {
        $cache = new FileAdapter($this->dir);
        $cache->set('item', 'content');
        $this->assertEquals('content', $cache->get('item'));
    }

    /**
     *
     */
    public function testDelete()
    {
        $cache = new FileAdapter($this->dir);
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
        $cache = new FileAdapter($this->dir, 0777);
        $cache->set('item', 'content');

        $perms = fileperms($this->dir . '/' . md5('item'));
        $this->assertEquals('0777', substr(sprintf('%o', $perms), -4));
    }

    /**
     *
     */
    public function testDeleteAll()
    {
        $cache = new FileAdapter($this->dir, 0777);
        $cache->set('item', 'content');
        $this->assertTrue(file_exists($this->dir . '/' . md5('item')));

        $cache->delete('*');
        $this->assertFalse(file_exists($this->dir . '/' . md5('item')));
    }

    /**
     *
     */
    public function testNamespaceSlash()
    {
        if (file_exists($this->dir.'/namespace')) {
            rmdir($this->dir.'/namespace');
        }

        $cache = new FileAdapter($this->dir, 0777);
        $cache->set('namespace/item', 'content');
        $this->assertTrue(file_exists($this->dir . '/namespace/' . md5('item')));

        $this->assertEquals('content', file_get_contents($this->dir . '/namespace/' . md5('item')));
        $this->assertEquals('content', $cache->get('namespace/item'));

        $cache->delete('namespace/*');
        $this->assertFalse(file_exists($this->dir . '/namespace/' . md5('item')));
    }
}
