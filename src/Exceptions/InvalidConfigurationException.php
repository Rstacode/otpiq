<?php
namespace Rstacode\Otpiq\Exceptions;

class InvalidConfigurationException extends \Exception
{
    public static function missingApiKey(): self
    {
        return new self('The OTPIQ API key is missing. Please set OTPIQ_API_KEY in your .env file');
    }

    public static function invalidApiKey(): self
    {
        return new self('The provided OTPIQ API key is invalid');
    }
}
