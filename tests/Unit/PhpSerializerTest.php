<?php
namespace Genkgo\Cache\Unit;

use Genkgo\Cache\AbstractTestCase;
use Genkgo\Cache\Serializer\PhpSerializer;

/**
 * Class PhpSerializerTest
 * @package Genkgo\Cache\Unit
 */
class PhpSerializerTest extends AbstractTestCase
{
    /**
     *
     */
    public function testScalar()
    {
        $serializer = new PhpSerializer();
        $this->assertEquals('N;', $serializer->serialize(null));
        $this->assertEquals('s:4:"test";', $serializer->serialize('test'));
        $this->assertEquals('i:1815;', $serializer->serialize(1815));

        $this->assertEquals(null, $serializer->deserialize('N;'));
        $this->assertEquals('test', $serializer->deserialize('s:4:"test";'));
        $this->assertEquals(1815, $serializer->deserialize('i:1815;'));
    }

    /**
     *
     */
    public function testArray()
    {
        $serializer = new PhpSerializer();
        $this->assertEquals('a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}', $serializer->serialize([1,2,3]));
        $this->assertEquals([1,2,3], $serializer->deserialize('a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}'));
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

        $serializer = new PhpSerializer();
        $this->assertEquals(
            'O:8:"stdClass":3:{s:1:"a";i:1;s:1:"b";s:4:"test";s:1:"c";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}',
            $serializer->serialize($object)
        );
        $this->assertEquals(
            $object,
            $serializer->deserialize(
                'O:8:"stdClass":3:{s:1:"a";i:1;s:1:"b";s:4:"test";s:1:"c";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}'
            )
        );
    }
}
