<?php
namespace Genkgo\Cache\Unit;

use Genkgo\Cache\AbstractTestCase;
use Genkgo\Cache\Adapter\NamespaceAdapter;
use Genkgo\Cache\CacheAdapterInterface;

/**
 * Class NamespaceAdapterTest
 * @package Genkgo\Cache\Unit
 */
class NamespaceAdapterTest extends AbstractTestCase
{
    /**
     *
     */
    public function testGet()
    {
        $mock = $this->getMock(CacheAdapterInterface::class);
        $mock->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('ns.item'))
            ->willReturn(null);

        $callbackCache = new NamespaceAdapter($mock, 'ns');
        $this->assertNull($callbackCache->get('item'));
    }

    /**
     *
     */
    public function testSet()
    {
        $mock = $this->getMock(CacheAdapterInterface::class);
        $mock->expects($this->at(0))
            ->method('set')
            ->with($this->equalTo('ns.item'), 'item content');

        $callbackCache = new NamespaceAdapter($mock, 'ns');
        $callbackCache->set('item', 'item content');
    }

    /**
     *
     */
    public function testDelete()
    {
        $mock = $this->getMock(CacheAdapterInterface::class);
        $mock->expects($this->at(0))
            ->method('delete')
            ->with($this->equalTo('ns.item'));

        $callbackCache = new NamespaceAdapter($mock, 'ns');
        $callbackCache->delete('item');
    }
}
