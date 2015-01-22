<?php
namespace Genkgo\Cache\Unit;

use Genkgo\Cache\AbstractTestCase;
use Genkgo\Cache\CacheAdapterInterface;
use Genkgo\Cache\Adapters\SimpleCallbackAdapter;

/**
 * Class SimpleCallbackAdapterTest
 * @package Genkgo\Cache\Unit
 */
class SimpleCallbackAdapterTest extends AbstractTestCase
{
    /**
     *
     */
    public function testGet()
    {
        $mock = $this->getMock(CacheAdapterInterface::class);
        $mock->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('item'))
            ->willReturn('item content');

        $callbackCache = new SimpleCallbackAdapter($mock);
        $item = $callbackCache->get('item', function () {
            return 'item content';
        });
        $this->assertEquals('item content', $item);
    }

    /**
     *
     */
    public function testRegenerate()
    {
        $mock = $this->getMock(CacheAdapterInterface::class);
        $mock->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('item'))
            ->willReturn(null);

        $mock->expects($this->at(1))
            ->method('set')
            ->with($this->equalTo('item'), $this->equalTo('item content'));

        $callbackCache = new SimpleCallbackAdapter($mock);
        $item = $callbackCache->get('item', function () {
            return 'item content';
        });
        $this->assertEquals('item content', $item);
    }
}
