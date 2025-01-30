<?php
namespace Rstacode\Otpiq\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Rstacode\Otpiq\DTOs\ProjectInfo;
use Rstacode\Otpiq\DTOs\SmsData;
use Rstacode\Otpiq\Exceptions\InvalidConfigurationException;
use Rstacode\Otpiq\Exceptions\OtpiqException;

class OtpiqService
{
    protected Client $client;
    protected string $apiKey;
    protected string $baseUrl = 'https://api.otpiq.com/api';

    public function __construct(?Client $client = null)
    {
        $this->validateConfiguration();

        $this->apiKey = config('otpiq.api_key');

        if ($client) {
            $this->client = $client;
        } else {
            $this->client = new Client([
                'base_uri'    => $this->baseUrl,
                'headers'     => [
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type'  => 'application/json',
                ],
                'http_errors' => true,
            ]);
        }
    }

    protected function validateConfiguration(): void
    {
        $apiKey = config('otpiq.api_key');

        if (empty($apiKey)) {
            throw InvalidConfigurationException::missingApiKey();
        }
    }

    protected function handleResponse($response)
    {
        $statusCode = $response->getStatusCode();
        $data       = json_decode($response->getBody()->getContents(), true);

        if ($statusCode === 401) {
            throw InvalidConfigurationException::invalidApiKey();
        }

        if ($statusCode === 400) {
            if (isset($data['error']) && str_contains($data['error'], 'Insufficient credit')) {
                throw OtpiqException::insufficientCredit(
                    $data['yourCredit'] ?? 0,
                    $data['requiredCredit'] ?? 0
                );
            }
            throw OtpiqException::invalidRequest($data['errors'] ?? [$data]);
        }

        if ($statusCode >= 400) {
            throw OtpiqException::apiError($data['message'] ?? 'Unknown error occurred');
        }

        return $data;
    }

    public function getProjectInfo(): ProjectInfo
    {
        try {
            $response = $this->client->get('info');
            $data     = $this->handleResponse($response);

            return ProjectInfo::fromArray($data);
        } catch (GuzzleException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
                throw InvalidConfigurationException::invalidApiKey();
            }
            throw OtpiqException::apiError($e->getMessage());
        }
    }

    public function sendSms(SmsData $smsData): array
    {
        try {
            $response = $this->client->post('sms', [
                'json' => $smsData->toArray(),
            ]);

            return $this->handleResponse($response);
        } catch (GuzzleException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
                throw InvalidConfigurationException::invalidApiKey();
            }
            throw OtpiqException::apiError($e->getMessage());
        }
    }

    public function getSenderIds(): array
    {
        try {
            $response = $this->client->get('sender-ids');
            return $this->handleResponse($response);
        } catch (GuzzleException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
                throw InvalidConfigurationException::invalidApiKey();
            }
            throw OtpiqException::apiError($e->getMessage());
        }
    }

    public function trackSms(string $smsId): array
    {
        try {
            $response = $this->client->get("sms/track/{$smsId}");
            return $this->handleResponse($response);
        } catch (GuzzleException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
                throw InvalidConfigurationException::invalidApiKey();
            }
            throw OtpiqException::apiError($e->getMessage());
        }
    }
}
