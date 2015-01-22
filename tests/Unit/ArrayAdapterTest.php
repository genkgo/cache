<?php
namespace Genkgo\Cache\Unit;

use Genkgo\Cache\AbstractTestCase;
use Genkgo\Cache\Adapters\ArrayAdapter;

/**
 * Class ArrayAdapterTest
 * @package Genkgo\Cache\Unit
 */
class ArrayAdapterTest extends AbstractTestCase
{
    /**
     *
     */
    public function testEmpty()
    {
        $cache = new ArrayAdapter();
        $this->assertNull($cache->get('item'));
    }

    /**
     *
     */
    public function testGetSet()
    {
        $cache = new ArrayAdapter();
        $cache->set('item', 'content');
        $this->assertEquals('content', $cache->get('item'));
    }

    /**
     *
     */
    public function testDelete()
    {
        $cache = new ArrayAdapter();
        $cache->set('item', 'content');
        $this->assertEquals('content', $cache->get('item'));
        $cache->delete('item');
        $this->assertNull($cache->get('item'));
    }
}
