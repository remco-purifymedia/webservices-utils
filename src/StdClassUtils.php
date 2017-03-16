<?php

namespace WebservicesNl\Utils;

/**
 * Class StdClassUtils
 */
class StdClassUtils
{
    /**
     * @param \stdClass $object
     *
     * @return array
     */
    public static function stdClassToArray(\stdClass $object)
    {
        $object = (array) $object;
        foreach ($object as &$value) {
            if ($value instanceof \stdClass) {
                $value = self::stdClassToArray($value);
                continue;
            }
            if (is_array($value)) {
                foreach ($value as &$arrayValue) {
                    if ($arrayValue instanceof \stdClass) {
                        $arrayValue = self::stdClassToArray($arrayValue);
                        continue;
                    }
                }
            }
        }

        return $object;
    }
}
