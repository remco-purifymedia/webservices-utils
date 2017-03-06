<?php

namespace WebservicesNl\Utils;

/**
 * Class FileSignatureUtils
 */
class FileSignatureUtils
{
    const PNG = '89504E470D0A1A0A';
    const JPG = 'FFD8FFE0';
    const PDF = '25504446';
    const GIF = '47494638';
    const BMP = '424D';
    const XML = '3C3F786D6C';

    /**
     * Determine whether the given string contains one of the registered signatures
     *
     * @param string $string    the string data to check
     * @param string $signature
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public static function hasSignature($string, $signature)
    {
        if (ctype_xdigit($signature) === false) {
            throw new \InvalidArgumentException(sprintf('Invalid HEX signature provided: %s', $signature));
        }
        $signatureChars = str_split($signature, 2);
        $hexChars = array_map(
            function ($val) {
                return strtoupper(bin2hex($val));
            },
            str_split(substr($string, 0, count($signatureChars)))
        );

        return $hexChars === $signatureChars;
    }
}
