<?php

namespace WebservicesNl\Utils;

/**
 * Class StdClassUtilsTest
 *
 * @package WebservicesNl\Utils
 */
class StdClassUtilsTest extends \PHPUnit_Framework_TestCase
{

    public function testStdClassToArray()
    {
        $subObject = new \stdClass();
        $subObject->{'hihi'} = 'bla';
        $subObject->{'booboo'} = 'mjam-mjam';

        $object = new \stdClass();

        $object->{'time'} = new \DateTime();
        $object->{'child1'} = new \stdClass();
        $object->{'child1'}->{'grandchild1'} = 'string';
        $object->{'child1'}->{'grandchild2'} = 123.456;
        $object->{'child2'} = new \stdClass();
        $object->{'child2'}->{'grandchild1'} = false;
        $object->{'child2'}->{'grandchild2'} = 123.456;
        $object->{'child2'}->{'grandchild3'} = new \stdClass();
        $object->{'child2'}->{'grandchild3'}->{'child1'} = 42;
        $object->{'child2'}->{'grandchild3'}->{'child2'} = null;
        $object->{'child2'}->{'grandchild3'}->{'child3'} = true;

        $object->{'pineapple'} = array(1, 2, 3);
        $object->{'apple'} = array('bla' => 1, 'blop' => 3, 'bleh' => $subObject);

        $array = StdClassUtils::stdClassToArray($object);

        $this->assertObject($object, $array);
    }

    private function assertObject($object, $array)
    {
        foreach ($object as $key => $value) {
            if (is_array($value)) {
                $this->assertObject($object->{$key}, $array[$key]);
            }
            static::assertArrayHasKey($key, $array);
            if ($value instanceof \stdClass) {
                $this->assertObject($value, $array[$key]);
            }
        }
    }
}
