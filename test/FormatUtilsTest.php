<?php

namespace WebservicesNl\Utils\test;

use WebservicesNl\Utils\FormatUtils;

/**
 * Class FormatUtilsTest.
 */
class FormatUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Not a valid format
     */
    public function testBadFormat()
    {
        FormatUtils::formatBytes(100, 3, 'imperial');
    }

    /**
     * test the formatting of bytes of various lengths and formats.
     */
    public function testFormatBytes()
    {
        self::assertSame('10 B', FormatUtils::formatBytes(10));
        self::assertSame('1000.00 B', FormatUtils::formatBytes(1000, 2, 'binary'));

        self::assertSame('100 B', FormatUtils::formatBytes(100));
        self::assertSame('1 kB', FormatUtils::formatBytes(1025));
        self::assertSame('1.0 kB', FormatUtils::formatBytes(1025, 1));
        self::assertSame('1.03 kB', FormatUtils::formatBytes(1025, 2));
        self::assertSame('1.0 KiB', FormatUtils::formatBytes(1025, 1, 'binary'));
        self::assertSame('1.00 KiB', FormatUtils::formatBytes(1025, 2, 'binary'));

        self::assertSame('1 kB', FormatUtils::formatBytes(1000));
        self::assertSame('1000 B', FormatUtils::formatBytes(1000, 0, 'binary'));

        self::assertSame('15 kB', FormatUtils::formatBytes(15245, 0));
        self::assertSame('16 kB', FormatUtils::formatBytes(15645, 0));
        self::assertSame('15.6 kB', FormatUtils::formatBytes(15645, 1));
        self::assertSame('15.65 kB', FormatUtils::formatBytes(15645, 2));

        self::assertSame('16 kB', FormatUtils::formatBytes(15945));
        self::assertSame('15.95 kB', FormatUtils::formatBytes(15945, 2));
        self::assertSame('15.945 kB', FormatUtils::formatBytes(15945, 3));
        self::assertSame('15.57 KiB', FormatUtils::formatBytes(15945, 2, 'binary'));
        self::assertSame('15.571 KiB', FormatUtils::formatBytes(15945, 3, 'binary'));

        self::assertSame('16 MB', FormatUtils::formatBytes(15945987));
        self::assertSame('15.95 MB', FormatUtils::formatBytes(15945987, 2));
        self::assertSame('15.21 MiB', FormatUtils::formatBytes(15945987, 2, 'binary'));
        self::assertSame('15.2073 MiB', FormatUtils::formatBytes(15945987, 4, 'binary'));

        self::assertSame('2 GB', FormatUtils::formatBytes(2000000000, 0));
        self::assertSame('2 GiB', FormatUtils::formatBytes(2000000000, 0, 'binary'));
        self::assertSame('1.86 GiB', FormatUtils::formatBytes(2000000000, 2, 'binary'));

        self::assertSame('3 TB', FormatUtils::formatBytes(3000000000000, 0));
        self::assertSame('1 TB', FormatUtils::formatBytes(1000000000000, 0));

        self::assertSame('1 PB', FormatUtils::formatBytes(1000000000000000, 0));
        self::assertSame('9 PB', FormatUtils::formatBytes(9000000000000000, 0));
        self::assertSame('8 PiB', FormatUtils::formatBytes(9000000000000000, 0, 'binary'));
    }
}
