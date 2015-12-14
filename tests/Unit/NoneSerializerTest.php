<?php
namespace Genkgo\Cache\Unit;

use Genkgo\Cache\AbstractTestCase;
use Genkgo\Cache\Serializer\NoneSerializer;

/**
 * Class NoneSerializerTest
 * @package Genkgo\Cache\Unit
 */
class NoneSerializerTest extends AbstractTestCase
{
    /**
     *
     */
    public function testScalar()
    {
        $serializer = new NoneSerializer();
        $this->assertEquals(null, $serializer->serialize(null));
        $this->assertEquals('test', $serializer->serialize('test'));
        $this->assertEquals(1815, $serializer->serialize(1815));

        $this->assertEquals(null, $serializer->deserialize(null));
        $this->assertEquals('test', $serializer->deserialize('test'));
        $this->assertEquals(1815, $serializer->deserialize(1815));
    }

    /**
     *
     */
    public function testArray()
    {
        $serializer = new NoneSerializer();
        $this->assertEquals([1,2,3], $serializer->serialize([1,2,3]));
        $this->assertEquals([1,2,3], $serializer->deserialize([1,2,3]));
    }

    /**
     *
     */
    public function testObject()
    {
        $object = new \stdClass();
        $object->a = 1;
        $object->b = 'test';
        $object->c = [1, 2, 3];

        $serializer = new NoneSerializer(false);
        $this->assertEquals($object, $serializer->serialize($object) );
        $this->assertEquals($object, $serializer->deserialize($object));
    }
}
