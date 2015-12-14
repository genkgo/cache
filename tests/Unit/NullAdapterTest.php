<?php
namespace Genkgo\Cache\Unit;

use Genkgo\Cache\AbstractTestCase;
use Genkgo\Cache\Adapter\NullAdapter;

/**
 * Class NullAdapterTest
 * @package Genkgo\Cache\Unit
 */
class NullAdapterTest extends AbstractTestCase
{
    /**
     *
     */
    public function testEmpty()
    {
        $cache = new NullAdapter();
        $this->assertNull($cache->get('item'));
    }

    /**
     *
     */
    public function testGetSet()
    {
        $cache = new NullAdapter();
        $cache->set('item', 'content');
        $this->assertNull($cache->get('item'));
    }

    /**
     *
     */
    public function testDelete()
    {
        $cache = new NullAdapter();
        $cache->delete('item');
        $this->assertNull($cache->get('item'));
    }
}
