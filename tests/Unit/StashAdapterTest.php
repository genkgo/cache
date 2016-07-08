<?php
namespace Genkgo\Cache\Unit;

use Genkgo\Cache\AbstractTestCase;
use Genkgo\Cache\Adapter\StashAdapter;
use Genkgo\Cache\Serializer\JsonSerializer;
use Genkgo\Cache\Serializer\NoneSerializer;
use Stash\Pool;

/**
 * Class ArrayAdapterTest
 * @package Genkgo\Cache\Unit
 */
class StashAdapterTest extends AbstractTestCase
{
    /**
     *
     */
    public function testEmpty()
    {
        $cache = new StashAdapter(new Pool(), new NoneSerializer());
        $this->assertNull($cache->get('item'));
    }

    /**
     *
     */
    public function testGetSet()
    {
        $cache = new StashAdapter(new Pool(), new NoneSerializer());
        $cache->set('item', 'content');
        $this->assertEquals('content', $cache->get('item'));
    }

    /**
     *
     */
    public function testDelete()
    {
        $cache = new StashAdapter(new Pool(), new NoneSerializer());
        $cache->set('item', 'content');
        $this->assertEquals('content', $cache->get('item'));
        $cache->delete('item');
        $this->assertNull($cache->get('item'));
    }

    /**
     *
     */
    public function testArray()
    {
        $cache = new StashAdapter(new Pool(), new JsonSerializer());
        $cache->set('item', ['content']);
        $this->assertEquals(['content'], $cache->get('item'));
    }
}
