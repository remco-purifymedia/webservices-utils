<?php

namespace WebservicesNl\Utils\Test;

use League\FactoryMuffin\FactoryMuffin;
use League\FactoryMuffin\Faker\Facade as Faker;
use WebservicesNl\Utils\ArrayUtils;

/**
 * Class ArrayUtilsTest.
 */
class ArrayUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FactoryMuffin
     */
    protected static $fm;

    /**
     * Test flatten Array.
     */
    public function testFlattenArray()
    {
        /** @var \WebservicesNl\Utils\Test\Entities\Project $project */
        $project = static::$fm->instance('WebservicesNl\Utils\Test\Entities\Project');
        $flatArray = ArrayUtils::flattenArray($project->toArray());

        // check if each value is present in the flat array
        return self::assertValueInNestedArray($flatArray);
    }

    /**
     * Helper function.
     *
     * @param array $array
     *
     * @return bool
     */
    public static function assertValueInNestedArray(array $array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                return self::assertValueInNestedArray($value);
            }
            self::assertContains($value, $array);
        }
        return true;
    }

    /**
     * Test flatten Array.
     */
    public function testExplodeArrayOnKeys()
    {
        /** @var \WebservicesNl\Utils\Test\Entities\Project $project */
        $project = static::$fm->instance('WebservicesNl\Utils\Test\Entities\Project');
        $delimiter = '_';
        $flatArray = ArrayUtils::flattenArray($project->toArray(), $delimiter);

        $explodedArray = ArrayUtils::explodeArrayOnKeys($flatArray, '_');
        $explodedArray2 = ArrayUtils::explodeArrayOnKeys($flatArray, '_', 0);

        self::assertEquals($project->toArray(), $explodedArray);
        self::assertEquals($flatArray, $explodedArray2);
    }

    /**
     * Test if array associative or numerical (starting from 0, 1, 2..).
     */
    public function testArrayIsAssociative()
    {
        /** @var array $projectArray */
        $projectArray = static::$fm->instance('WebservicesNl\Utils\Test\Entities\Project')->toArray();
        $numericArray = ['bla', 'nope'];

        self::assertTrue(ArrayUtils::isAssociativeArray($projectArray));
        self::assertFalse(ArrayUtils::isAssociativeArray($numericArray));

        self::assertTrue(ArrayUtils::isIndexedArray($numericArray));
        self::assertFalse(ArrayUtils::isIndexedArray($projectArray));
    }

    /**
     * Test the depth of an 'normal' array.
     */
    public function testArrayDepth()
    {
        $countArrays[] = [
            'depth' => 4,
            'value' => static::$fm->instance('WebservicesNl\Utils\Test\Entities\Project')->toArray()
        ];
        $countArrays[] = [
            'depth' => 3,
            'value' => static::$fm->instance('WebservicesNl\Utils\Test\Entities\User')->toArray()
        ];
        $countArrays[] = [
            'depth' => 2,
            'value' => static::$fm->instance('WebservicesNl\Utils\Test\Entities\Address')->toArray()
        ];
        $countArrays[] = [
            'depth' => 1,
            'value' => static::$fm->instance('WebservicesNl\Utils\Test\Entities\Geocode')->toArray()
        ];

        foreach ($countArrays as $item) {
            self::assertEquals($item['depth'], ArrayUtils::calculateArrayDepth($item['value']));
        }
    }

    /**
     * Test filter array on key.
     */
    public function testFilterArrayOnKey()
    {
        $projectArray = static::$fm->instance('WebservicesNl\Utils\Test\Entities\Project')->toArray();
        $filteredArray = ArrayUtils::filterArrayOnKey($projectArray, 'address');

        self::assertEquals($projectArray['address'], $filteredArray['address']);
    }

    /**
     * Test Array merge recursive.
     */
    public function testArrayMerge()
    {
        /** @var \WebservicesNl\Utils\Test\Entities\Project[] $projects */
        $projects = static::$fm->seed(2, 'WebservicesNl\Utils\Test\Entities\Project');

        $project0 = $projects[0]->toArray();
        $project1 = $projects[1]->toArray();

        // outcome of comparison should be completely equals to $projects1
        self::assertEquals($project1, ArrayUtils::mergeRecursive($project0, $project1), 'should be as $project1');

        // same values on different numerical order
        $target1 = ['red', 'green', 'blue'];
        $source1 = ['green', 'red', 'blue'];

        // different values on the same keys
        $target2 = ['red', 'green', 'blue'];
        $source2 = ['red', 'green', 'yellow', 'blue'];

        // nested examples
        $target3 = [0 => [1, 2], 'text' => ['bla', 'color' => 'red']];
        $source3 = [1 => [2, 1], 'text' => ['red', 'color' => 'blue']];

        // do some (weird) merging with and without combine unique values
        $result1 = ArrayUtils::mergeRecursive($target1, $source1, false);
        $result2 = ArrayUtils::mergeRecursive($target1, $source1, true);

        $result3 = ArrayUtils::mergeRecursive($target2, $source2, false);
        $result4 = ArrayUtils::mergeRecursive($target2, $source2, true);

        $result5 = ArrayUtils::mergeRecursive($target3, $source3, false);
        $result6 = ArrayUtils::mergeRecursive($target3, $source3, true);

        self::assertCount(6, $result1, 'indexed array: unique values should not not be merged');
        self::assertCount(3, $result2, 'indexed array: unique values should be unduplicated');
        self::assertArrayHasKey(6, $result3);
        self::assertArrayNotHasKey(6, $result4);
        self::assertCount(3, $result5['text'], 'existing value in target array should be preserved');
        self::assertCount(2, $result6['text'], 'existing value in target array should be copied onto existing key');
    }

    /**
     * Test the depth the array.
     */
    public function testReferencedArray()
    {
        // create an Array
        $value = static::$fm->instance('WebservicesNl\Utils\Test\Entities\Project')->toArray(); // 4 levels deep
        $value['somethingElse'] = $value; // 5 levels deep
        $depth = ArrayUtils::calculateArrayDepth($value);

        $value['somethingMore'] = &$value['somethingElse']; //should create 6th level

        // create some references inside the array
        self::assertEquals($depth, ArrayUtils::calculateArrayDepth($value, true));
    }

    /**
     * Test if all keys are present in haystack.
     */
    public function testHasAllKeys()
    {
        $haystack = [1 => [2, 1], 0 => 'bla', 'bla' => [2, 3]];

        self::assertTrue(ArrayUtils::hasAllKeys($haystack, 0, 1));
        self::assertFalse(ArrayUtils::hasAllKeys($haystack, 1, 2));
    }

    /**
     * Test if any key is present in haystack.
     */
    public function testHasAnyKeys()
    {
        $haystack = [1 => 0, 0 => 'bla', 'bla' => [2, 3]];

        self::assertTrue(ArrayUtils::hasAnyKey($haystack, 0, 1, 2, 'pie'));
        self::assertFalse(ArrayUtils::hasAnyKey($haystack, 'pie', 'bananas'));
    }

    /**
     * Test if any key is present in haystack.
     */
    public function testFillMissingKeys()
    {
        $start = 1;
        $end = 12;
        $middle = (int) floor($end / 2);

        $testArray = [
            $start => (string) $start,
            $middle => (string) $middle,
            $end => (string) $end,
        ];

        $result = ArrayUtils::fillMissingKeys($testArray);

        self::assertCount($end - $start + 1, $result);
        self::assertCount($middle, array_slice($result, $middle, null, true));
        self::assertEquals($result[$middle], (string) $testArray[$middle]);

        reset($result);
        self::assertEquals($start, current($result));
        end($result);
        self::assertEquals($end, current($result));
    }

    /**
     * testAssociativeArrayIsNotFilledWithKeys.
     */
    public function testAssociativeArrayIsNotFilledWithKeys()
    {
        $associative = [
            'bla' => 'green',
            'green' => 'bla',
            3, 4
        ];
        // test empty array
        self::assertEquals(
            $associative,
            ArrayUtils::fillMissingKeys($associative),
            'associative array should be returned immediately'
        );
    }

    /**
     * testEmptyArrayIsNotFilled.
     */
    public function testEmptyArrayIsNotFilled()
    {
        // test empty array
        self::assertEmpty(ArrayUtils::fillMissingKeys([]), 'empty array should be returned immediately');
    }

    /**
     * Setup beforeClass.
     */
    public static function setupBeforeClass()
    {
        static::$fm = new FactoryMuffin();
        static::$fm->loadFactories(__DIR__ . '/Factories');
        Faker::instance()->setLocale('nl_NL');
    }
}
