<?php
namespace Rstacode\Otpiq;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Rstacode\Otpiq\Exceptions\OtpiqApiException;

class OtpiqService
{
    protected Client $client;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct(string $apiKey, $baseUrl = "https://api.otpiq.com/api/")
    {
        $this->apiKey  = $apiKey;
        $this->baseUrl = $baseUrl;
        $this->client  = new Client([
            'base_uri' => $this->baseUrl,
            'headers'  => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ],
        ]);
    }
    public function getProjectInfo(): array
    {
        return $this->request('GET', 'info');
    }

    public function sendSms(array $data): array
    {
        $this->validateSmsData($data);
        return $this->request('POST', 'sms', $data);
    }

    public function getSenderIds(): array
    {
        return $this->request('GET', 'sender-ids');
    }

    public function trackSms(string $smsId): array
    {
        return $this->request('GET', "sms/track/{$smsId}");
    }

    protected function validateSmsData(array $data): void
    {
        $requiredFields = match ($data['smsType'] ?? null) {
            'verification' => ['phoneNumber', 'smsType', 'verificationCode'],
            'custom' => ['phoneNumber', 'smsType', 'customMessage', 'senderId'],
            default => throw new \InvalidArgumentException('Invalid smsType')
        };

        foreach ($requiredFields as $field) {
            if (! isset($data[$field])) {
                throw new \InvalidArgumentException("Missing required field: {$field}");
            }
        }
    }

    protected function request(string $method, string $uri, array $data = []): array
    {
        try {
            $options = [];

            // Add JSON data only for POST requests
            if ($method === 'POST' && ! empty($data)) {
                $options['json'] = $data;
            }

            $response = $this->client->request($method, $uri, $options);

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            throw OtpiqApiException::fromGuzzleException($e);
        }
    }
}
