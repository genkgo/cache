<?php
namespace Genkgo\Cache\Unit;

use Genkgo\Cache\AbstractTestCase;
use Genkgo\Cache\Serializer\JsonSerializer;

/**
 * Class JsonSerializerTest
 * @package Genkgo\Cache\Unit
 */
class JsonSerializerTest extends AbstractTestCase
{
    /**
     *
     */
    public function testScalar()
    {
        $serializer = new JsonSerializer();
        $this->assertEquals('null', $serializer->serialize(null));
        $this->assertEquals('"test"', $serializer->serialize('test'));
        $this->assertEquals('1815', $serializer->serialize(1815));

        $this->assertEquals(null, $serializer->deserialize('null'));
        $this->assertEquals('test', $serializer->deserialize('"test"'));
        $this->assertEquals(1815, $serializer->deserialize('1815'));
    }

    /**
     *
     */
    public function testArray()
    {
        $serializer = new JsonSerializer();
        $this->assertEquals('[1,2,3]', $serializer->serialize([1,2,3]));
        $this->assertEquals([1,2,3], $serializer->deserialize('[1,2,3]'));
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

        $serializer = new JsonSerializer(false);
        $this->assertEquals(
            '{"a":1,"b":"test","c":[1,2,3]}',
            $serializer->serialize($object)
        );
        $this->assertEquals(
            $object,
            $serializer->deserialize(
                '{"a":1,"b":"test","c":[1,2,3]}'
            )
        );
    }
}
