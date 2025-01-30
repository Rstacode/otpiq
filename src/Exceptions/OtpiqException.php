<?php
/**
 * Custom exception class for Otpiq package
 * کڵاسی تایبەت بۆ هەڵەکانی پاکێجی Otpiq
 *
 * This exception is thrown when:
 * ئەم هەڵەیە دێتە پێشەوە کاتێک:
 * - Invalid phone number format / فۆرماتی ژمارەی مۆبایل هەڵەیە
 * - Missing API key / کلیلی API بوونی نییە
 * - API request fails / داواکاری API سەرکەوتوو نەبوو
 *
 * @package Rstacode\Otpiq\Exceptions
 */
namespace Rstacode\Otpiq\Exceptions;

use Exception;

class OtpiqException extends Exception
{
    /**
     * Create a new OtpiqException instance
     * دروستکردنی نموونەیەکی نوێی OtpiqException
     *
     * @param string $message Error message / پەیامی هەڵە
     * @param int $code Error code / کۆدی هەڵە
     */
    public function __construct(string $message, int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
