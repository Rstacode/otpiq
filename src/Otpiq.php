<?php
/**
 * Otpiq - A Laravel package for OTP verification services
 * پاکێجی لاراڤێڵ بۆ خزمەتگوزاریەکانی سەلماندنی OTP
 *
 * @package Rstacode\Otpiq
 */
namespace Rstacode\Otpiq;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Rstacode\Otpiq\Exceptions\OtpiqException;

class Otpiq
{
    /**
     * HTTP client instance
     * نموونەی کلاینتی HTTP
     */
    protected $client;

    /**
     * Package configuration
     * ڕێکخستنەکانی پاکێج
     */
    protected $config;

    /**
     * API base URL
     * URL-ی سەرەکی API
     */
    protected $baseUrl;

    /**
     * Initialize the Otpiq instance
     * دەستپێکردنی نموونەی Otpiq
     *
     * @param array $config Configuration array ڕێکخستنەکان
     * @param Client|null $client Optional HTTP client نموونەی کلاینتی HTTP (ئارەزوومەندانە)
     */
    public function __construct(array $config, ?Client $client = null)
    {
        $this->validateConfig($config);
        $this->config  = $config;
        $this->baseUrl = 'https://api.otpiq.com/api';
        $this->client  = $client ?? $this->initializeClient();
    }

    /**
     * Initialize HTTP client
     * دەستپێکردنی کلاینتی HTTP
     *
     * @return Client
     */
    protected function initializeClient(): Client
    {
        return new Client([
            'base_uri' => $this->baseUrl,
            'headers'  => [
                'Authorization' => 'Bearer ' . $this->config['api_key'],
                'Content-Type'  => 'application/json',
            ],
        ]);
    }

    /**
     * Get project information and remaining credits
     * وەرگرتنی زانیاری پڕۆژە و کرێدیتی ماوە
     *
     * @return object
     * @throws OtpiqException
     */
    public function getProjectInfo()
    {
        try {
            $response = $this->client->request('GET', '/info');
            return json_decode($response->getBody()->getContents());
        } catch (GuzzleException $e) {
            throw new OtpiqException($e->getMessage());
        }
    }

    /**
     * Send OTP or custom message
     * ناردنی OTP یان نامەی تایبەت
     *
     * @param string $phoneNumber Phone number ژمارەی مۆبایل
     * @param string $type Message type (verification/custom) جۆری نامە
     * @param array $options Additional options بژاردەی زیادە
     * @return object
     * @throws OtpiqException
     */
    public function send(string $phoneNumber, string $type = 'verification', array $options = [])
    {
        $this->validatePhoneNumber($phoneNumber);

        try {
            $response = $this->client->request('POST', '/sms', [
                'json' => array_merge([
                    'phoneNumber' => $phoneNumber,
                    'smsType'     => $type,
                ], $options),
            ]);
            return json_decode($response->getBody()->getContents());
        } catch (GuzzleException $e) {
            throw new OtpiqException($e->getMessage());
        }
    }

    /**
     * Validate phone number format
     * دڵنیابوون لە فۆرماتی ژمارەی مۆبایل
     *
     * @param string $phoneNumber
     * @throws OtpiqException
     */
    protected function validatePhoneNumber(string $phoneNumber): void
    {
        if (! preg_match('/^[0-9]{10,}$/', $phoneNumber)) {
            throw new OtpiqException('Invalid phone number format');
        }
    }

    /**
     * Validate configuration
     * دڵنیابوون لە ڕێکخستنەکان
     *
     * @param array $config
     * @throws OtpiqException
     */
    protected function validateConfig(array $config): void
    {
        if (empty($config['api_key'])) {
            throw new OtpiqException('API key is required');
        }
    }
}
