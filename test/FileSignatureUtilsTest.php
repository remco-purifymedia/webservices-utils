<?php

namespace WebservicesNl\Utils\Test;

use WebservicesNl\Utils\FileSignatureUtils;

/**
 * Class FileSignatureUtilTest
 */
class FileSignatureUtilsTest extends \PHPUnit_Framework_TestCase
{
    public function testValidPdfSignature()
    {
        self::assertTrue(FileSignatureUtils::hasSignature(
            '%PDF We all love chocolate, don\'t we?', FileSignatureUtils::PDF)
        );
    }

    public function testInvalidPdfSignature()
    {
        self::assertFalse(FileSignatureUtils::hasSignature('no PDF', FileSignatureUtils::PDF));
    }
}
