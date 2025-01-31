<?php
namespace Rstacode\Otpiq\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Rstacode\Otpiq\OtpiqService;
use Rstacode\Otpiq\Tests\TestCase;

class OtpiqServiceTest extends TestCase
{
    protected $otpiqService;
    protected $mockClient;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock Guzzle Client
        $this->mockClient   = Mockery::mock(Client::class);
        $this->otpiqService = new OtpiqService('fake_api_key');
        $this->setProtectedProperty($this->otpiqService, 'client', $this->mockClient);
    }

    public function testGetProjectInfo()
    {
        // Mock API Response
        $this->mockClient->shouldReceive('request')
            ->once()
            ->with('GET', 'info', Mockery::any()) // <-- گۆڕانکاری لێرە
            ->andReturn(new Response(200, [], json_encode([
                'projectName' => 'Test Project',
                'credit'      => 1000,
            ])));

        $result = $this->otpiqService->getProjectInfo();

        $this->assertEquals('Test Project', $result['projectName']);
        $this->assertEquals(1000, $result['credit']);
    }
    public function testSendSms()
    {
        // Mock API Response
        $this->mockClient->shouldReceive('request')
            ->once()
            ->with('POST', 'sms', Mockery::any())
            ->andReturn(new Response(200, [], json_encode([
                'message'         => 'SMS task created successfully',
                'smsId'           => 'sms-1234567890',
                'remainingCredit' => 920,
                'provider'        => 'telegram',
                'status'          => 'pending',
            ])));

        $result = $this->otpiqService->sendSms([
            'phoneNumber'      => '9647501234567',
            'smsType'          => 'verification',
            'verificationCode' => '123456',
            'provider'         => 'auto',
        ]);

        $this->assertEquals('SMS task created successfully', $result['message']);
        $this->assertEquals('sms-1234567890', $result['smsId']);
    }

    public function testSendSmsValidationError()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->otpiqService->sendSms([
            'phoneNumber' => '9647501234567',
            'smsType'     => 'verification',
            // Missing verificationCode
        ]);
    }

    protected function setProtectedProperty($object, $property, $value)
    {
        $reflection = new \ReflectionClass($object);
        $property   = $reflection->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}
