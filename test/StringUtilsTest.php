<?php

namespace WebservicesNl\Utils\Test;

use WebservicesNl\Utils\StringUtils;

/**
 * Class StringUtilsTest.
 */
class StringUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider stringExamples
     *
     * @param array $item
     */
    public function testStartsWith($item)
    {
        self::assertTrue(StringUtils::stringStartsWith($item['original'], $item['startsWith']));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Not a string
     * @dataProvider             dataTypes
     *
     * @param string $string
     * @param string $text
     */
    public function testbadArgumentStringStartsWith($string, $text)
    {
        StringUtils::stringStartsWith($string, $text);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Not a string
     * @dataProvider             dataTypes
     *
     * @param string $string
     * @param string $text
     */
    public function testbadArgumentStringEndWith($string, $text)
    {
        StringUtils::stringEndsWith($string, $text);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Not a string
     * @dataProvider             dataTypes
     *
     * @param string $string
     * @param string $text
     */
    public function testbadArgumentRemovePrefix($string, $text)
    {
        StringUtils::removePrefix($string, $text);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Not a string
     * @dataProvider             dataTypes
     *
     * @param string $string
     */
    public function testbadArgumentToCamelCaps($string)
    {
        StringUtils::toCamelCase($string, true);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Not a string
     * @dataProvider             dataTypes
     *
     * @param string $string
     */
    public function testbadArgumentToUnderscored($string)
    {
        StringUtils::toUnderscore($string);
    }

    /**
     * @return array
     */
    public function dataTypes()
    {
        return [
            [new \DateTime(), 'string'], [[], 'string'], [(bool) 1, 'string'], [new \stdClass(), 'string']
        ];
    }

    /**
     * @return array
     */
    public function stringExamples()
    {
        return [
            [[
                'original' => 'RussianGirlsHeyWhatchaWannaDrink?!',
                'startsWith' => 'RussianGirls',
                'endsWith' => 'WhatchaWannaDrink?!',
                'underscored' => 'russian_girls_hey_whatcha_wanna_drink',
                'camelCased' => 'RussianGirlsHeyWhatchaWannaDrink?!'
            ]],
            [[
                'original' => 'this is just a string 123',
                'startsWith' => 'this is',
                'endsWith' => 'string 123',
                'underscored' => 'this_is_just_a_string_123',
                'camelCased' => 'ThisIsJustAString123'
            ]],
            [[
                'original' => '__funny__case__',
                'startsWith' => '__funny',
                'endsWith' => '__case__',
                'underscored' => 'funny_case',
                'camelCased' => 'FunnyCase'
            ]],
            [[
                'original' => 'A strange string to pass, maybe with some ø, æ, Ë, ë, å characters',
                'startsWith' => 'A strange',
                'endsWith' => 'characters',
                'underscored' => 'a_strange_string_to_pass_maybe_with_some_characters',
                'camelCased' => 'AStrangeStringToPassMaybeWithSome ø, æ, Ë, ë, å characters',
            ]],
            [[
                'original' => 'I like_XML___DDD?!_d',
                'startsWith' => 'I like',
                'endsWith' => '?!_d',
                'underscored' => 'i_like_xml_ddd_d',
                'camelCased' => 'ILikeXMLDDD?!D',
            ]], [[
                'original' => 'I like_Camel_case_and cannot lie',
                'startsWith' => 'I like',
                'endsWith' => 'not lie',
                'underscored' => 'i_like_camel_case_and_cannot_lie',
                'camelCased' => 'ILikeCamelCaseAndCannotLie',
            ]], [[
                'original' => '12_3_4_56',
                'startsWith' => '12_3_',
                'endsWith' => '4_56',
                'underscored' => '12_3_4_56',
                'camelCased' => '123456',
            ]], [[
                'original' => '__-__',
                'startsWith' => '__-',
                'endsWith' => '-__',
                'underscored' => '',
                'camelCased' => '-',
            ]],
        ];
    }

    /**
     * @dataProvider stringExamples
     *
     * @param array $item
     */
    public function testEndsWith($item)
    {
        self::assertTrue(StringUtils::stringEndsWith($item['original'], $item['endsWith']));
    }

    /**
     * @dataProvider stringExamples
     *
     * @param array $item
     */
    public function testToUnderscore($item)
    {
        $underScore = StringUtils::toUnderscore($item['original']);
        $camelCaps = StringUtils::toCamelCase($underScore);
        $reUnderScored = StringUtils::toUnderscore($camelCaps);

        self::assertEquals($item['underscored'], $underScore);
        self::assertEquals($reUnderScored, $underScore);
    }

    /**
     * @dataProvider stringExamples
     *
     * @param array $item
     */
    public function testToCamelCase($item)
    {
        $underscored = StringUtils::toUnderscore($item['original']);

        self::assertEquals($item['underscored'], $underscored);
    }

    /**
     * @dataProvider stringExamples
     *
     * @param array $item
     */
    public function testRemovePrefixTest($item)
    {
        $trimmed = StringUtils::removePrefix($item['original'], $item['startsWith']);

        self::assertStringStartsNotWith($item['startsWith'], $trimmed);
        self::assertStringStartsWith($trimmed, $trimmed);
    }

    /**
     * Some successful cases.
     *
     * @dataProvider validFilenameProvider
     *
     * @param string $filename
     * @param string $expectedValue
     */
    public function testSomeCorrectCases($filename, $expectedValue)
    {
        static::assertSame(StringUtils::getFileExtension($filename), $expectedValue);
    }

    /**
     * Test case with some invalid inputs.
     *
     * @dataProvider invalidFilenameProvider
     * @expectedException \InvalidArgumentException
     *
     * @param mixed $input
     */
    public function testInvalidInputs($input)
    {
        StringUtils::getFileExtension($input);
    }

    /**
     * @return array
     */
    public function validFilenameProvider()
    {
        return [
            ['folder1/folder2/file.BMP', 'bmp'],
            ['folder1/folder2/file.part2.abc', 'abc'],
            ['file.12345', '12345'],
            ['filename', '']
        ];
    }

    /**
     * @return array
     */
    public function invalidFilenameProvider()
    {
        return [
            [null],
            [1234],
            [new \stdClass()],
            [true],
            [-6.5]
        ];
    }
}
