<?php

namespace WebservicesNl\Utils;

use Ddeboer\Transcoder\Transcoder;

/**
 * Class StringUtils.
 */
class StringUtils
{
    /**
     * @param string $filename
     * @param bool   $lowercase
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public static function getFileExtension($filename, $lowercase = true)
    {
        if (false === is_string($filename)) {
            throw new \InvalidArgumentException(sprintf('Filename must be a string, %s given', gettype($filename)));
        }
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        return $lowercase ? strtolower($extension) : $extension;
    }

    /**
     * Determine if a string ends with a particular sequence.
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     *
     * @throws \InvalidArgumentException
     */
    public static function stringEndsWith($haystack, $needle)
    {
        if (!is_string($haystack) || !is_string($needle)) {
            throw new \InvalidArgumentException('Not a string');
        }

        return (strrpos($haystack, $needle) + strlen($needle)) === strlen($haystack);
    }

    /**
     * Determine if a string starts with a particular sequence.
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     *
     * @throws \InvalidArgumentException
     */
    public static function stringStartsWith($haystack, $needle)
    {
        if (!is_string($haystack) || !is_string($needle)) {
            throw new \InvalidArgumentException('Not a string');
        }

        return strpos($haystack, $needle) === 0;
    }

    /**
     * Remove the prefix from the provided string.
     *
     * @param string $haystack
     * @param string $prefix
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public static function removePrefix($haystack, $prefix)
    {
        if (!is_string($haystack) || !is_string($prefix)) {
            throw new \InvalidArgumentException('Not a string');
        }

        if (strpos($haystack, $prefix) === 0) {
            $haystack = substr($haystack, strlen($prefix));
        }

        return $haystack;
    }

    /**
     * Convert strings with underscores into CamelCase (for getters and setters).
     *
     * @param string $string        The string to convert
     * @param bool   $firstCharCaps camelCase or CamelCase
     *
     * @return string The converted string
     *
     * @throws \Ddeboer\Transcoder\Exception\UnsupportedEncodingException
     * @throws \Ddeboer\Transcoder\Exception\ExtensionMissingException
     * @throws \InvalidArgumentException
     */
    public static function toCamelCase($string, $firstCharCaps = true)
    {
        if (!is_string($string)) {
            throw new \InvalidArgumentException('Not a string');
        }

        $transcoder = Transcoder::create();
        $string = $transcoder->transcode($string);

        if ($firstCharCaps === true) {
            $string = ucfirst($string);
        }

        return preg_replace_callback(
            '/_\?([a-z])/',
            function ($string) {
                return strtoupper($string[1]);
            },
            $string
        );
    }

    /**
     * Internal function for toUnderscore.
     *
     * @param string $word
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    private static function wordToUnderscored($word)
    {
        return strtolower(
            trim(
                preg_replace(
                    '~[^a-z^\d]+~i',
                    '_',
                    preg_replace(
                        '~([a-z\d])([A-Z]|\d)~',
                        '\1_\2',
                        preg_replace('~([A-Z]|\d+)([A-Z][a-z]|\d)~', '\1_\2', $word)
                    )
                ),
                '_'
            )
        );
    }

    /**
     * Converts a string to underscore version.
     * Also tries to filter out higher UTF-8 chars.
     *
     * @param string $string
     *
     * @return string
     *
     * @throws \Ddeboer\Transcoder\Exception\UnsupportedEncodingException
     * @throws \Ddeboer\Transcoder\Exception\ExtensionMissingException
     * @throws \InvalidArgumentException
     */
    public static function toUnderscore($string)
    {
        if (!is_string($string)) {
            throw new \InvalidArgumentException('Not a string');
        }

        $transCoder = Transcoder::create('ASCII');
        $string = $transCoder->transcode(trim($string));
        $words = explode(' ', $string);

        return implode('_', array_filter(array_map([static::class, 'wordToUnderscored'], $words)));
    }
}
