<?php

namespace WebservicesNl\Utils;

/**
 * Class JsonUtils.
 *
 * Wrapper around JsonUtils Encode and Decode. Throws the appropriate error message when encoding/decoding fails.
 */
class JsonUtils
{
    /**
     * @var array JSON error messages
     */
    protected static $errorMessages = [
        JSON_ERROR_NONE => 'No error has occurred',
        JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
        JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX => 'Syntax error',
        JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
        JSON_ERROR_RECURSION => 'One or more recursive references in the value to be encoded',
        JSON_ERROR_INF_OR_NAN => 'One or more NAN or INF values in the value to be encoded',
        JSON_ERROR_UNSUPPORTED_TYPE => 'A value of a type that cannot be encoded was given',
    ];

    /**
     * Decode a JSON string into a php representation.
     *
     * @param string $json    string to convert
     * @param bool   $assoc   return as associative array
     * @param int    $options JSON options
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     *
     * @return mixed
     */
    public static function decode($json, $assoc = false, $options = 0)
    {
        if (!is_string($json)) {
            throw new \InvalidArgumentException('Argument json is not a string');
        }
        $result = json_decode($json, (bool) $assoc, 512, (int) $options);
        if ($result === null) {
            throw new \RuntimeException(static::$errorMessages[json_last_error()]);
        }

        return $result;
    }

    /**
     * Encode a PHP value into a JSON representation.
     *
     * @param mixed $value
     * @param int   $options [optional]
     *
     * @return string
     *
     * @throws \RuntimeException
     *
     * @link http://php.net/manual/en/function.json-encode.php
     */
    public static function encode($value, $options = 0)
    {
        $result = json_encode($value, (int) $options);
        // @codeCoverageIgnoreStart
        if ($result === false) {
            throw new \RuntimeException(static::$errorMessages[json_last_error()]);
        }
        // @codeCoverageIgnoreEnd

        return $result;
    }
}
