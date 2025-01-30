<?php
namespace Rstacode\Otpiq\DTOs;

class SmsData
{
    public function __construct(
        public string $phoneNumber,
        public string $smsType,
        public ?string $verificationCode = null,
        public ?string $customMessage = null,
        public ?string $senderId = null,
        public string $provider = 'auto'
    ) {
        $this->validate();
    }

    protected function validate(): void
    {
        if ($this->smsType === 'verification' && empty($this->verificationCode)) {
            throw new \InvalidArgumentException('Verification code is required when smsType is verification');
        }

        if ($this->smsType === 'custom' && (empty($this->customMessage) || empty($this->senderId))) {
            throw new \InvalidArgumentException('Custom message and sender ID are required when smsType is custom');
        }
    }

    public function toArray(): array
    {
        return array_filter([
            'phoneNumber'      => $this->phoneNumber,
            'smsType'          => $this->smsType,
            'verificationCode' => $this->verificationCode,
            'customMessage'    => $this->customMessage,
            'senderId'         => $this->senderId,
            'provider'         => $this->provider,
        ]);
    }
}
