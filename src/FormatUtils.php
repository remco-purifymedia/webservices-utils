<?php

namespace WebservicesNl\Utils;

/**
 * Class FormatUtils.
 */
class FormatUtils
{
    /**
     * @var array
     */
    protected static $formats = [
        'decimal' => [ //  SI Prefixes (decimal)
            'mod' => 1000,
            'units' => ['B', 'kB', 'MB', 'GB', 'TB', 'PB'],
        ],
        'binary' => [ // IEC prefixes (binary)
            'mod' => 1024,
            'units' => ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'],
        ]
    ];

    /**
     * Return a formatted string (from bytes) in decimal or binary.
     *
     * @param int|string|float $size
     * @param int              $precision
     * @param string           $format either binary or decimal
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function formatBytes($size, $precision = 0, $format = 'decimal')
    {
        if (!array_key_exists($format, self::$formats)) {
            throw new \InvalidArgumentException('Not a valid format');
        }

        $format = self::$formats[$format];
        $precision = (int)$precision;

        /** @var float $base */
        $base = log((float)$size, $format['mod']);
        $key = (int)floor($base);

        $value = round(pow($format['mod'], $base - floor($base)), $precision);

        return sprintf('%.' . $precision . 'f %s', $value, $format['units'][$key]);
    }
}
