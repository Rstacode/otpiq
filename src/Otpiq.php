<?php

namespace Rstacode\Otpiq;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class Otpiq
{
    protected $client;
    protected $config;
    protected $baseUrl = 'https://api.otpiq.com/api';

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Bearer ' . $config['api_key'],
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    /**
     * Send OTP or custom message
     */
    public function send(string $phoneNumber, string $type = 'verification', string $message = null)
    {
        $response = $this->client->post('/sms', [
            'json' => [
                'phoneNumber' => $phoneNumber,
                'smsType' => $type,
                'customMessage' => $message,
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Verify OTP code
     */
    public function verify(string $phoneNumber, string $code)
    {
        $response = $this->client->post('/sms/verify', [
            'json' => [
                'phoneNumber' => $phoneNumber,
                'verificationCode' => $code,
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Track SMS status
     */
    public function track(string $smsId)
    {
        $response = $this->client->get("/sms/track/{$smsId}");
        return json_decode($response->getBody(), true);
    }
}