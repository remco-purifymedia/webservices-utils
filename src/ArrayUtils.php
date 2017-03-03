<?php

namespace WebservicesNl\Utils;

/**
 * Class ArrayUtils.
 */
class ArrayUtils
{
    /**
     * Explode an array on string.
     *
     * @param array  $array
     * @param string $explodeOn
     * @param int    $limit     |null
     *
     * @return array
     */
    public static function explodeArrayOnKeys($array, $explodeOn, $limit = null)
    {
        $result = [];
        foreach ($array as $path => $value) {
            $temp = &$result;
            $tempArray = ($limit === null) ? explode($explodeOn, $path) : explode($explodeOn, $path, $limit);
            foreach ($tempArray as $key) {
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
     * Filter an array key.
     *
     * @param array  $array
     * @param string $value
     *
     * @return array
     */
    public static function filterArrayOnKey(array $array, $value)
    {
        return array_filter(
            $array,
            function ($key) use ($value) {
                return strpos($key, $value) === 0;
            },
            ARRAY_FILTER_USE_KEY
        );
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
                /** @noinspection AdditionOperationOnArraysInspection */
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
     * Calculate the depth of an array.
     * Be aware of array with references to other places in the same array. This can cause memory exhaustion.
     *
     * @param array $array
     * @param bool  $deReference deReference should the array values be de-referenced first?
     *
     * @return int
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function calculateArrayDepth(array $array, $deReference = false)
    {
        if ($deReference === true) {
            /** @var array $array */
            $array = unserialize(serialize($array));
        }

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
     * Merge two arrays together (kinda). And a bit of replace.
     * Copy the values of source array into the target array, when the keys are numerical .
     * this function is scheduled for replacement, please use array_merge or array_replace.
     *
     * @param array $targetArray   targetArray
     * @param array $sourceArray   sourceArray
     * @param bool  $combineUnique
     *
     * @deprecated please use array_merge or array_replace
     *
     * @return array
     */
    public static function mergeRecursive(array $targetArray, array $sourceArray, $combineUnique = true)
    {
        // check for each key in source array if it is present in the target array
        foreach ($sourceArray as $key => $value) {
            if (array_key_exists($key, $targetArray)) {
                if (is_int($key)) {
                    if ($combineUnique === false || !in_array($value, $targetArray, true)) {
                        $targetArray[] = $value;
                    }
                } elseif (is_array($value) && is_array($targetArray[$key])) {
                    $targetArray[$key] = static::mergeRecursive($targetArray[$key], $value, $combineUnique);
                } else {
                    // replace key
                    $targetArray[$key] = $value;
                }
            } else {
                // replace key
                $targetArray[$key] = $value;
            }
        }

        return $targetArray;
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

    /**
     * Convert an array to camelcased key version
     *
     * @param array $array
     *
     * @throws \InvalidArgumentException
     * @throws \Ddeboer\Transcoder\Exception\UnsupportedEncodingException
     * @throws \Ddeboer\Transcoder\Exception\ExtensionMissingException
     *
     * @return array
     */
    public static function toUnderScore(array $array = [])
    {
        $output = [];
        foreach ($array as $key => $value) {
            $key = StringUtils::toUnderscore($key);
            if (is_array($value)) {
                $value = static::toUnderScore($value);
            }
            $output[$key] = $value;
        }

        return $output;
    }
}
