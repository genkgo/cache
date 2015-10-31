<?php
namespace Genkgo\Cache\Unit;

use Genkgo\Cache\AbstractTestCase;
use Genkgo\Cache\Adapters\PredisAdapter;
use Predis\ClientInterface;
use Predis\Configuration\OptionsInterface;
use Predis\Configuration\PrefixOption;
use stdClass;

/**
 * Class PredisAdapterTest
 * @package Genkgo\Cache\Unit
 */
class PredisAdapterTest extends AbstractTestCase
{
    /**
     *
     */
    public function testGet()
    {
        $client = $this->getMock(ClientInterface::class);
        $client->expects($this->at(0))->method('__call')->with('get', ['item'])->willReturn(null);

        $cache = new PredisAdapter($client);
        $this->assertNull($cache->get('item'));
    }

    /**
     *
     */
    public function testSet()
    {
        $client = $this->getMock(ClientInterface::class);
        $client->expects($this->at(0))->method('__call')->with('set', ['item', 'value', null, null])->willReturn(null);
        $client->expects($this->at(1))->method('__call')->with('get', ['item'])->willReturn('value');

        $cache = new PredisAdapter($client);
        $cache->set('item', 'value');
        $this->assertEquals('value', $cache->get('item'));
    }

    /**
     *
     */
    public function testDelete()
    {
        $client = $this->getMock(ClientInterface::class);
        $client->expects($this->at(0))->method('__call')->with('set', ['item', 'value', null, null]);
        $client->expects($this->at(1))->method('__call')->with('del', [['item']]);

        $cache = new PredisAdapter($client);
        $cache->set('item', 'value');
        $cache->delete('item');
    }

    /**
     *
     */
    public function testTtl()
    {
        $client = $this->getMock(ClientInterface::class);
        $client->expects($this->at(0))->method('__call')->with('set', ['item', 'value', 'ex', 300]);

        $cache = new PredisAdapter($client, 300);
        $cache->set('item', 'value');
    }

    /**
     *
     */
    public function testDeleteAll()
    {
        $client = $this->getMock(ClientInterface::class);
        $client->expects($this->at(0))->method('__call')->with('set', ['item', 'value', null, null]);
        $client->expects($this->at(1))->method('__call')->with('keys', ['*'])->willReturn(['item']);
        $client->expects($this->at(2))->method('getOptions')->willReturn(new stdClass());
        $client->expects($this->at(3))->method('__call')->with('del', [['item']]);

        $cache = new PredisAdapter($client);
        $cache->set('item', 'value');
        $cache->delete('*');
    }

    /**
     *
     */
    public function testDeleteAllWithPrefix()
    {
        $options = new stdClass;
        $options->prefix = (new PrefixOption())->filter($this->getMock(OptionsInterface::class), 'cache:');

        $client = $this->getMock(ClientInterface::class);
        $client->expects($this->at(0))->method('__call')->with('set', ['item', 'value', null, null]);
        $client->expects($this->at(1))->method('__call')->with('keys', ['cache:*'])->willReturn(['cache:item']);
        $client->expects($this->at(2))->method('getOptions')->willReturn($options);
        $client->expects($this->at(3))->method('__call')->with('del', [['item']]);

        $cache = new PredisAdapter($client);
        $cache->set('item', 'value');
        $cache->delete('cache:*');
    }
}
