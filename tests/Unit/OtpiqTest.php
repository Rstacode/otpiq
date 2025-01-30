<?php
namespace Rstacode\Otpiq\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Orchestra\Testbench\TestCase;
use Rstacode\Otpiq\DTOs\SmsData;
use Rstacode\Otpiq\Exceptions\InvalidConfigurationException;
use Rstacode\Otpiq\Exceptions\OtpiqException;
use Rstacode\Otpiq\OtpiqServiceProvider;
use Rstacode\Otpiq\Services\OtpiqService;

class OtpiqTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [OtpiqServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('otpiq.api_key', 'test-api-key');
    }

    protected function mockOtpiqService(Response $response): OtpiqService
    {
        $mock         = new MockHandler([$response]);
        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $service = new OtpiqService($client);
        $this->app->instance(OtpiqService::class, $service);
        $this->app->instance('otpiq', $service);

        return $service;
    }

    /** @test */
    public function it_can_get_project_info()
    {
        $service = $this->mockOtpiqService(
            new Response(200, [], json_encode([
                'projectName' => 'Test Project',
                'credit'      => 1000,
            ]))
        );

        $projectInfo = $service->getProjectInfo();

        $this->assertEquals('Test Project', $projectInfo->projectName);
        $this->assertEquals(1000, $projectInfo->credit);
    }

    /** @test */
    public function it_can_send_verification_sms()
    {
        $service = $this->mockOtpiqService(
            new Response(200, [], json_encode([
                'message'         => 'SMS task created successfully',
                'smsId'           => 'sms-1234567890',
                'remainingCredit' => 920,
                'provider'        => 'telegram',
                'status'          => 'pending',
            ]))
        );

        $smsData = new SmsData(
            phoneNumber: '9647501234567',
            smsType: 'verification',
            verificationCode: '123456'
        );

        $response = $service->sendSms($smsData);

        $this->assertEquals('sms-1234567890', $response['smsId']);
        $this->assertEquals('pending', $response['status']);
    }

    /** @test */
    public function it_throws_exception_for_invalid_api_key()
    {
        $this->expectException(InvalidConfigurationException::class);

        $service = $this->mockOtpiqService(
            new Response(401, [], json_encode([
                'message' => 'Unauthorized, please use your project api key',
            ]))
        );

        $service->getProjectInfo();
    }

    /** @test */
    public function it_throws_exception_for_insufficient_credit()
    {
        $this->expectException(OtpiqException::class);
        $this->expectExceptionMessage('Insufficient credit');

        $service = $this->mockOtpiqService(
            new Response(400, [], json_encode([
                'error'          => 'Insufficient credit, please add more credit',
                'yourCredit'     => 500,
                'requiredCredit' => 1000,
            ]))
        );

        $smsData = new SmsData(
            phoneNumber: '9647501234567',
            smsType: 'verification',
            verificationCode: '123456'
        );

        $service->sendSms($smsData);
    }

    /** @test */
    public function it_can_get_sender_ids()
    {
        $service = $this->mockOtpiqService(
            new Response(200, [], json_encode([
                'senderIds' => [
                    [
                        'id'        => 'sender-123',
                        'senderId'  => 'TestBrand',
                        'status'    => 'accepted',
                        'createdAt' => '2024-01-01T00:00:00.000Z',
                    ],
                ],
            ]))
        );

        $response = $service->getSenderIds();

        $this->assertIsArray($response['senderIds']);
        $this->assertEquals('TestBrand', $response['senderIds'][0]['senderId']);
    }

    /** @test */
    public function it_can_track_sms()
    {
        $service = $this->mockOtpiqService(
            new Response(200, [], json_encode([
                'status'      => 'delivered',
                'phoneNumber' => '964750000000',
                'smsId'       => 'sms-1234567890',
                'cost'        => 80,
            ]))
        );

        $response = $service->trackSms('sms-1234567890');

        $this->assertEquals('delivered', $response['status']);
        $this->assertEquals(80, $response['cost']);
    }
}
