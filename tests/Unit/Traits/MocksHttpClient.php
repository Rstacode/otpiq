<?php
namespace Rstacode\Otpiq\Tests\Unit\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Rstacode\Otpiq\Services\OtpiqService;

trait MocksHttpClient
{
    protected function mockClient(array $responses): void
    {
        $mock = new MockHandler(array_map(function ($response) {
            return new Response(
                $response['status'] ?? 200,
                $response['headers'] ?? [],
                json_encode($response['body'] ?? [])
            );
        }, $responses));

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client([
            'handler' => $handlerStack,
            'headers' => [
                'Authorization' => 'Bearer test-api-key',
                'Content-Type'  => 'application/json',
            ],
        ]);

        $this->app->bind(OtpiqService::class, function () use ($client) {
            return new OtpiqService($client);
        });
    }
}
