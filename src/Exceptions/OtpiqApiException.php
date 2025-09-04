<?php

namespace Rstacode\Otpiq\Exceptions;

use GuzzleHttp\Exception\GuzzleException;

class OtpiqApiException extends \Exception
{
    protected array $errors = [];
    protected ?array $responseData = null;

    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null, array $errors = [], array $responseData = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
        $this->responseData = $responseData;
    }

    public static function fromGuzzleException(GuzzleException $e): self
    {
        $message = $e->getMessage();
        $code = $e->getCode();
        $errors = [];
        $responseData = null;

        if ($e->hasResponse()) {
            $response = $e->getResponse();
            $body = json_decode($response->getBody(), true);
            $responseData = $body;

            $message = $body['message'] ?? $message;
            $code = $response->getStatusCode();
            $errors = $body['errors'] ?? [];

            // Handle validation errors
            if (isset($body['errors']) && is_array($body['errors'])) {
                $errors = $body['errors'];
            }
        }

        return new self("OTPIQ API Error: {$message}", $code, $e, $errors, $responseData);
    }

    /**
     * Get validation errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if exception has validation errors
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get the full response data
     */
    public function getResponseData(): ?array
    {
        return $this->responseData;
    }

    /**
     * Check if the error is related to insufficient credit
     */
    public function isCreditError(): bool
    {
        return $this->getCode() === 402 ||
            str_contains(strtolower($this->getMessage()), 'credit') ||
            str_contains(strtolower($this->getMessage()), 'insufficient');
    }

    /**
     * Check if the error is related to authentication
     */
    public function isAuthError(): bool
    {
        return in_array($this->getCode(), [401, 403]);
    }

    /**
     * Check if the error is a validation error
     */
    public function isValidationError(): bool
    {
        return $this->getCode() === 422 && $this->hasErrors();
    }

    /**
     * Get first error message if available
     */
    public function getFirstError(): ?string
    {
        if (!$this->hasErrors()) {
            return null;
        }

        $firstError = reset($this->errors);
        return is_array($firstError) ? reset($firstError) : $firstError;
    }
}
