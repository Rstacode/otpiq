<?php
namespace Rstacode\Otpiq\Exceptions;

use GuzzleHttp\Exception\GuzzleException;

class OtpiqApiException extends \Exception
{
    public static function fromGuzzleException(GuzzleException $e): self
    {
        $message = $e->getMessage();
        $code    = $e->getCode();

        if ($e->hasResponse()) {
            $response = $e->getResponse();
            $body     = json_decode($response->getBody(), true);
            $message  = $body['message'] ?? $message;
            $code     = $response->getStatusCode();
        }

        return new self("OTPIQ API Error: {$message}", $code);
    }
}
