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

    public function __construct(string $apiKey, string $baseUrl = 'https://api.otpiq.com/api/')
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl, '/') . '/';
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'timeout' => config('otpiq.timeout', 30),
        ]);
    }

    public function getProjectInfo(): array
    {
        return $this->request('GET', 'info');
    }

    public function sendSms(array $data): array
    {
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

    protected function request(string $method, string $uri, array $data = []): array
    {
        try {
            $options = $method === 'POST' && !empty($data) ? ['json' => $data] : [];
            $response = $this->client->request($method, $uri, $options);

            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            throw OtpiqApiException::fromGuzzleException($e);
        }
    }
}
