<?php

namespace WebservicesNl\Utils;

/**
 * Class ArrayUtils.
 *
 */
class ArrayUtils
{
    /**
     * @param array  $array
     * @param string $explodeOn
     * @param int    $limit
     *
     * @return array
     */
    public function explodeArrayOnKeys($array, $explodeOn, $limit)
    {
        $result = [];
        foreach ($array as $path => $value) {
            $temp = &$result;
            foreach (explode($explodeOn, $path, $limit) as $key) {
                $temp = &$temp[$key];
            }
            $temp = $value;
        }

        return $result;
    }

    /**
     * Fill the array adding the missing keys from 0 to the max key, setting values to $value, and orders it.
     *
     * @param array  $array
     * @param string $value
     *
     * @return array
     */
    public static function fillMissingKeys(array $array, $value = '')
    {
        if (count($array) === 0 || self::isAssociativeArray($array) === true) {
            return $array;
        }

        // sort and reset pointer to first element
        ksort($array);
        $min = key($array);
        end($array);
        $max = key($array);

        for ($i = $min; $i < $max; $i++) {
            if (array_key_exists($i, $array) === false) {
                $array[$i] = $value;
            }
        }

        ksort($array);

        return $array;
    }

    /**
     * @param array  $array
     * @param string $value
     *
     * @return array
     */
    public static function filterArrayOnKey(array $array, $value)
    {
        return array_filter($array, function ($key) use ($value) {
            return strpos($key, $value) === 0;
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param array  $array
     * @param string $separator
     *
     * @return array
     */
    public static function flattenArray(array $array, $separator = '_')
    {
        $resultArray = [];
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $resultArray += self::prefixArray(self::flattenArray($val), $key, $separator);
            } else {
                $resultArray[$key] = $val;
            }
        }

        return $resultArray;
    }

    /**
     * @param array  $array
     * @param        $prefix
     * @param string $separator
     *
     * @return array
     */
    public static function prefixArray(array $array, $prefix, $separator = '_')
    {
        return array_combine(
            array_map(
                function ($key) use ($prefix, $separator) {
                    return $prefix . $separator . $key;
                },
                array_keys($array)
            ),
            $array
        );
    }

    /**
     * Determine if an array is associative or not.
     *
     * @param array $input
     *
     * @return bool
     */
    public static function isAssociativeArray(array $input)
    {
        return (bool) count(array_filter(array_keys($input), 'is_string'));
    }

    /**
     * Returns whether the array has numeric keys in order 0, 1, ... n or not.
     *
     * @param array $array
     *
     * @return bool
     */
    public static function isIndexedArray(array $array)
    {
        return array_keys($array) === range(0, count($array) - 1);
    }

    /**
     * Reindex the most nested element in an array.
     *
     * @param array $values
     * @param int   $index
     *
     * @return array
     */
    public static function reindexNestedElementInArray(array $values, $index)
    {
        $depth = static::calculateArrayDepth($values);
        $result = [];
        if ($depth === 1) {
            $result[$index] = $values;
        } else {
            $result[key($values)] = static::reindexNestedElementInArray(current($values), $index);
        }

        return $result;
    }

    /**
     * Calculate the depth of an array.
     *
     * @param array $array
     *
     * @return int
     */
    public static function calculateArrayDepth(array $array)
    {
        $maxDepth = 1;

        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = static::calculateArrayDepth($value) + 1;

                if ($depth > $maxDepth) {
                    $maxDepth = $depth;
                }
            }
        }

        return $maxDepth;
    }

    /**
     * Trim all the string in the array.
     *
     * @param array $strings
     *
     * @return array
     */
    public static function trimArray(array $strings)
    {
        return array_map('trim', $strings);
    }

    /**
     * @param array $a
     * @param array $b
     * @param bool  $combineUnique
     *
     * @return array
     */
    public static function mergeRecursive(array $a, array $b, $combineUnique = true)
    {
        foreach ($b as $key => $value) {
            if (array_key_exists($key, $a)) {
                if (is_int($key)) {
                    if (false === $combineUnique || false === in_array($value, $a, true)) {
                        $a[] = $value;
                    }
                } elseif (is_array($value) && is_array($a[$key])) {
                    $a[$key] = static::mergeRecursive($a[$key], $value, $combineUnique);
                } else {
                    $a[$key] = $value;
                }
            } else {
                $a[$key] = $value;
            }
        }

        return $a;
    }

    /**
     * @param array $haystack
     * @param array ...$needles
     *
     * @return bool
     */
    public static function hasAllKeys(array $haystack, ...$needles)
    {
        foreach ($needles as $needle) {
            if (array_key_exists($needle, $haystack) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $haystack
     * @param array ...$needles
     *
     * @return bool
     */
    public static function hasAnyKey(array $haystack, ...$needles)
    {
        foreach ($needles as $needle) {
            if (array_key_exists($needle, $haystack)) {
                return true;
            }
        }

        return false;
    }
}
