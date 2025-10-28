<?php

namespace Rstacode\Otpiq\Exceptions;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;

class OtpiqApiException extends Exception
{
    protected array $errors = [];
    protected ?array $responseData = null;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        array $errors = [],
        ?array $responseData = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
        $this->responseData = $responseData;
    }

    public static function fromGuzzleException(GuzzleException $e): self
    {
        $message = $e->getMessage();
        $code = 0;
        $errors = [];
        $responseData = null;

        if ($e->hasResponse()) {
            $response = $e->getResponse();
            $body = json_decode($response->getBody()->getContents(), true) ?? [];
            $responseData = $body;
            $message = $body['message'] ?? $body['error'] ?? $message;
            $code = $response->getStatusCode();
            $errors = $body['errors'] ?? [];
        }

        return new self("OTPIQ API Error: {$message}", $code, $e, $errors, $responseData);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getResponseData(): ?array
    {
        return $this->responseData;
    }

    public function isCreditError(): bool
    {
        $message = strtolower($this->getMessage());
        return str_contains($message, 'credit') ||
            str_contains($message, 'insufficient') ||
            isset($this->responseData['yourCredit']);
    }

    public function isAuthError(): bool
    {
        return $this->getCode() === 401;
    }

    public function isValidationError(): bool
    {
        return $this->getCode() === 400 && $this->hasErrors();
    }

    public function isRateLimitError(): bool
    {
        return $this->getCode() === 429;
    }

    public function isTrialModeError(): bool
    {
        $message = strtolower($this->getMessage());
        return str_contains($message, 'trial mode');
    }

    public function isSpendingThresholdError(): bool
    {
        return isset($this->responseData['spendingThreshold']);
    }

    public function isSenderIdError(): bool
    {
        $message = strtolower($this->getMessage());
        return str_contains($message, 'senderid');
    }

    public function getFirstError(): ?string
    {
        if (!$this->hasErrors()) {
            return null;
        }

        $firstError = reset($this->errors);
        return is_array($firstError) ? reset($firstError) : $firstError;
    }

    public function getRemainingCredit(): ?int
    {
        return $this->responseData['yourCredit'] ??
            $this->responseData['remainingCredit'] ??
            null;
    }

    public function getRequiredCredit(): ?int
    {
        return $this->responseData['requiredCredit'] ?? null;
    }

    public function getRateLimitWaitMinutes(): ?int
    {
        return $this->responseData['waitMinutes'] ?? null;
    }
}
