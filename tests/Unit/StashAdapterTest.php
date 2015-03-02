<?php
namespace Genkgo\Cache\Unit;

use Genkgo\Cache\AbstractTestCase;
use Genkgo\Cache\Adapters\StashAdapter;
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
        $cache = new StashAdapter(new Pool());
        $this->assertNull($cache->get('item'));
    }

    /**
     *
     */
    public function testGetSet()
    {
        $cache = new StashAdapter(new Pool());
        $cache->set('item', 'content');
        $this->assertEquals('content', $cache->get('item'));
    }

    /**
     *
     */
    public function testDelete()
    {
        $cache = new StashAdapter(new Pool());
        $cache->set('item', 'content');
        $this->assertEquals('content', $cache->get('item'));
        $cache->delete('item');
        $this->assertNull($cache->get('item'));
    }
}
