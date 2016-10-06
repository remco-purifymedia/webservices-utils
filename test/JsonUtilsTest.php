<?php

namespace WebservicesNl\Utils\Test;

use League\FactoryMuffin\FactoryMuffin;
use League\FactoryMuffin\Faker\Facade as Faker;
use WebservicesNl\Utils\JsonUtils;

/**
 * Class JsonUtilsTest.
 */
class JsonUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FactoryMuffin
     */
    protected static $fm;

    /**
     * Setup beforeClass.
     */
    public static function setupBeforeClass()
    {
        static::$fm = new FactoryMuffin();
        static::$fm->loadFactories(__DIR__ . '/Factories');
        Faker::instance()->setLocale('nl_NL');
    }

    /**
     * Test round trip array.
     */
    public function testRoundTripArray()
    {
        /** @var \WebservicesNl\Utils\Test\Entities\Project $project */
        $project = static::$fm->instance('WebservicesNl\Utils\Test\Entities\Project');

        $project = $project->toArray();
        $string = JsonUtils::encode($project);
        $project2 = JsonUtils::decode($string, true);

        self::assertEquals($project2, $project);
    }

    /**
     * Test not a string.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument json is not a string
     */
    public function testNotAStringArgument()
    {
        /** @var \WebservicesNl\Utils\Test\Entities\Project $project */
        JsonUtils::decode([]);
    }

    /**
     * Test bad syntax string.
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Syntax error
     */
    public function testDecodeBadSyntaxStringArgument()
    {
        /** @var \WebservicesNl\Utils\Test\Entities\Project $project */
        JsonUtils::decode('"key":"a quoted "value" "');
    }

    /**
     * Test bad syntax string.
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Malformed UTF-8 characters, possibly incorrectly encoded
     */
    public function testDecodeMalformedStringArgument()
    {
        /** @var \WebservicesNl\Utils\Test\Entities\Project $project */
        JsonUtils::decode("\xB1\x31");
    }
}
