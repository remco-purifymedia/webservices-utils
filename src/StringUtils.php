<?php

namespace WebservicesNl\Utils;

/**
 * Class StringUtils
 *
 */
class StringUtils
{
    /**
     * Format string with the number of seconds (from milliseconds)
     *
     * @param int $microseconds Time in microseconds
     *
     * @return string
     */
    public static function formatDuration($microseconds)
    {
        return $microseconds / 1000 . ' s';
    }

    /**
     * Return a formatted string (from bytes) in decimal or binary
     *
     * @param int|string $size
     * @param int        $precision
     * @param string     $format    either binary or decimal
     *
     * @return string
     */
    public static function formatBytes($size, $precision = 2, $format = 'binary')
    {
        $formats = [
            'decimal' => [ // IEC prefixes (binary)
                'units' => ['B', 'kB', 'MB', 'GB', 'TB', 'PB'],
                'mod' => 1000
            ],
            'binary' => [ // SI prefixes (decimal)
                'units' => ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'],
                'mod' => 1024
            ]
        ];

        $format = (!array_key_exists($format, $formats)) ? 'binary' : $format;
        $format = $formats[$format];

        /** @var float $base */
        $base = log((float) $size, $format['mod']);
        $key = (int) floor($base);

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $format['units'][$key];
    }

    /**
     * Determine if a string (haystack) starts and ends with a certain sequence (needle).
     *
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    public static function isStringSurroundedBy($haystack, $needle)
    {
        return static::stringEndsWith($haystack, $needle) && static::stringStartsWith($haystack, $needle);
    }

    /**
     * Determine if a string ends with a particular sequence.
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public static function stringEndsWith($haystack, $needle)
    {
        return (strrpos($haystack, $needle) + strlen($needle)) === strlen($haystack);
    }

    /**
     * Determine if a string starts with a particular sequence.
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public static function stringStartsWith($haystack, $needle)
    {
        return strpos($haystack, $needle) === 0;
    }

    /**
     * Remove the prefix from the provided string
     *
     * @param string $haystack
     * @param string $prefix
     *
     * @return string
     */
    public static function removePrefix($haystack, $prefix)
    {
        if (strpos($haystack, $prefix) === 0) {
            $haystack = substr($haystack, strlen($prefix));
        }

        return $haystack;
    }

    /**
     * Convert strings with underscores into CamelCase (for getters and setters)
     *
     * @param string $string        The string to convert
     * @param bool   $firstCharCaps camelCase or CamelCase
     *
     * @return string The converted string
     */
    public static function underscoreToCamelCase($string, $firstCharCaps = true)
    {
        if ($firstCharCaps === true) {
            $string = ucfirst($string);
        }

        return preg_replace_callback('/_([a-z])/', function ($string) {
            return strtoupper($string[1]);
        }, $string);
    }

    /**
     * A (possibly) better check on numeric string compared to the default PHP one, since it should detect also comma
     * separated values
     *
     * @param string $value
     *
     * @return bool
     */
    public static function isNumeric($value)
    {
        return preg_match('/^(-){0,1}([\d]+)(,[\d]{3})*([.][\d]){0,1}([\d]*)$/', $value) === 1;
    }
}
