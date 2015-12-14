<?php
namespace Genkgo\Cache\Unit;

use DateTime;
use DateTimeImmutable;
use Exception;
use Genkgo\Cache\AbstractTestCase;
use Genkgo\Cache\Adapter\StampedeCallbackAdapter;
use Genkgo\Cache\CacheAdapterInterface;

/**
 * Class StampedeCallbackAdapterTest
 * @package Genkgo\Cache\Unit
 */
class StampedeCallbackAdapterTest extends AbstractTestCase
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

        $callbackCache = new StampedeCallbackAdapter($mock, 10);
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
            ->with($this->equalTo('spitem'), $this->equalTo('locked'));

        $mock->expects($this->at(2))
            ->method('set')
            ->with($this->equalTo('item'), $this->equalTo('item content'));

        $mock->expects($this->at(3))
            ->method('set')
            ->with($this->equalTo('spitem'), $this->matchesRegularExpression($this->getIso8601Regex()));

        $callbackCache = new StampedeCallbackAdapter($mock, 10);
        $item = $callbackCache->get('item', function () {
            return 'item content';
        });
        $this->assertEquals('item content', $item);
    }

    /**
     *
     */
    public function testProtection()
    {
        $mock = $this->getMock(CacheAdapterInterface::class);
        $mock->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('item'))
            ->willReturn('item content');

        $mock->expects($this->at(1))
            ->method('get')
            ->with($this->equalTo('spitem'))
            ->willReturn($this->getIsoDateInThePast());

        $mock->expects($this->at(2))
            ->method('set')
            ->with($this->equalTo('spitem'), $this->equalTo('locked'));

        $mock->expects($this->at(3))
            ->method('get')
            ->with($this->equalTo('item'))
            ->willReturn('item content');

        $mock->expects($this->at(4))
            ->method('get')
            ->with($this->equalTo('spitem'))
            ->willReturn('locked');

        $mock->expects($this->at(5))
            ->method('set')
            ->with($this->equalTo('item'), $this->equalTo('regenerated content'));

        $mock->expects($this->at(6))
            ->method('set')
            ->with($this->equalTo('spitem'), $this->matchesRegularExpression($this->getIso8601Regex()));

        $callbackCache = new StampedeCallbackAdapter($mock, 300);
        $item = $callbackCache->get('item', function () use ($callbackCache) {
            $this->assertEquals('item content', $callbackCache->get('item', function () {}));
            return 'regenerated content';
        });
        $this->assertEquals('regenerated content', $item);
    }

    /**
     *
     */
    public function testException()
    {
        $this->setExpectedException(Exception::class);

        $mock = $this->getMock(CacheAdapterInterface::class);
        $mock->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('item'))
            ->willReturn(null);

        $mock->expects($this->at(1))
            ->method('set')
            ->with($this->equalTo('spitem'), $this->equalTo('locked'));

        $mock->expects($this->at(2))
            ->method('set')
            ->with($this->equalTo('spitem'), $this->matchesRegularExpression($this->getIso8601Regex()));

        $callbackCache = new StampedeCallbackAdapter($mock, 10);
        $callbackCache->get('item', function () {
            throw new Exception('Due to some reason the cache cannot be regenerated');
        });
    }

    /**
     *
     */
    public function testUseInvalidDateOnException()
    {
        $mock = $this->getMock(CacheAdapterInterface::class);
        $mock->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('item'))
            ->willReturn('item content');

        $mock->expects($this->at(1))
            ->method('get')
            ->with($this->equalTo('spitem'))
            ->willReturn($this->getIsoDateInThePast());

        $mock->expects($this->at(2))
            ->method('set')
            ->with($this->equalTo('spitem'), $this->equalTo('locked'));

        $mock->expects($this->at(3))
            ->method('set')
            ->with($this->equalTo('item'), $this->equalTo('item content'));

        $mock->expects($this->at(4))
            ->method('set')
            ->with($this->equalTo('spitem'), $this->matchesRegularExpression($this->getIso8601Regex()));

        $callbackCache = new StampedeCallbackAdapter($mock, 10);
        $callbackCache->useInvalidDataOnException();
        $item = $callbackCache->get('item', function () {
            throw new Exception('Due to some reason the cache cannot be regenerated');
        });
        $this->assertEquals('item content', $item);
    }

    /**
     * @return DateTimeImmutable
     */
    private function getIsoDateInThePast()
    {
        return (new DateTimeImmutable('yesterday'))->format(DateTime::ISO8601);
    }

    private function getIso8601Regex()
    {
        return '/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/';
    }
}
