<?php
namespace Rstacode\Otpiq\Exceptions;

class OtpiqException extends \Exception
{
    protected array $errors;

    public function __construct(string $message, array $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public static function insufficientCredit(int $yourCredit, int $requiredCredit): self
    {
        return new self(
            "Insufficient credit. You have {$yourCredit} credits but need {$requiredCredit}",
            ['yourCredit' => $yourCredit, 'requiredCredit' => $requiredCredit]
        );
    }

    public static function invalidRequest(array $errors): self
    {
        return new self('Invalid request parameters', $errors);
    }

    public static function apiError(string $message): self
    {
        return new self("OTPIQ API Error: {$message}");
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
