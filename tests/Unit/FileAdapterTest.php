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
    /**
     *
     */
    public function testEmpty()
    {
        $cache = new FileAdapter(sys_get_temp_dir());
        $this->assertNull($cache->get('item'));
    }

    /**
     *
     */
    public function testGetSet()
    {
        $cache = new FileAdapter(sys_get_temp_dir());
        $cache->set('item', 'content');
        $this->assertEquals('content', $cache->get('item'));
    }

    /**
     *
     */
    public function testDelete()
    {
        $cache = new FileAdapter(sys_get_temp_dir());
        $cache->set('item', 'content');
        $this->assertEquals('content', $cache->get('item'));
        $cache->delete('item');
        $this->assertNull($cache->get('item'));
    }
}
