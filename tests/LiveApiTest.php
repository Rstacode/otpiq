<?php
namespace Rstacode\Otpiq\Tests;

use PHPUnit\Framework\TestCase;
use Rstacode\Otpiq\Otpiq;

class LiveApiTest extends TestCase
{
    protected $otpiq;
    protected $phoneNumber = '9647704695176'; // ژمارەی خۆت لێرە دابنێ

    protected function setUp(): void
    {
        $apiKey = getenv('OTPIQ_API_KEY') ?: 'your-api-key-here'; // API key ی خۆت لێرە دابنێ

        $this->otpiq = new Otpiq([
            'api_key' => $apiKey,
        ]);
    }

    /** @test */
    public function it_can_get_project_info()
    {
        $info = $this->otpiq->getProjectInfo();

        $this->assertIsObject($info);
        $this->assertNotEmpty($info->projectName);
        $this->assertIsInt($info->credit);

        // Print for manual verification
        echo "\nProject Info Test:";
        echo "\nProject Name: " . $info->projectName;
        echo "\nCredits: " . $info->credit . "\n";
    }

    /** @test */
    public function it_can_send_verification_sms()
    {
        $response = $this->otpiq->send(
            $this->phoneNumber,
            'verification',
            ['verificationCode' => '123456']
        );

        $this->assertIsObject($response);
        $this->assertNotEmpty($response->smsId);
        $this->assertNotEmpty($response->status);

        // Save smsId for tracking test
        file_put_contents(__DIR__ . '/last_sms_id.txt', $response->smsId);

        // Print for manual verification
        echo "\nSend SMS Test:";
        echo "\nSMS ID: " . $response->smsId;
        echo "\nStatus: " . $response->status;
        echo "\nRemaining Credit: " . $response->remainingCredit . "\n";
    }

    /** @test */
    public function it_can_send_custom_sms()
    {
        $response = $this->otpiq->send(
            $this->phoneNumber,
            'custom',
            [
                'customMessage' => 'Test message from Otpiq package',
                'senderId'      => 'TestApp',
            ]
        );

        $this->assertIsObject($response);
        $this->assertNotEmpty($response->smsId);

        echo "\nCustom SMS Test:";
        echo "\nSMS ID: " . $response->smsId;
        echo "\nStatus: " . $response->status . "\n";
    }

    /** @test */
    public function it_can_get_sender_ids()
    {
        $senderIds = $this->otpiq->getSenderIds();

        $this->assertIsObject($senderIds);
        $this->assertIsArray($senderIds->items);

        echo "\nSender IDs Test:";
        foreach ($senderIds->items as $sender) {
            echo "\nID: " . $sender->senderId . " (Status: " . $sender->status . ")";
        }
        echo "\n";
    }

    /** @test */
    public function it_can_track_sms()
    {
        // Get last SMS ID from file
        $smsId = file_get_contents(__DIR__ . '/last_sms_id.txt');

        $tracking = $this->otpiq->track($smsId);

        $this->assertIsObject($tracking);
        $this->assertNotEmpty($tracking->status);

        echo "\nTracking Test:";
        echo "\nSMS ID: " . $tracking->smsId;
        echo "\nStatus: " . $tracking->status;
        echo "\nCost: " . $tracking->cost . "\n";
    }

    /** @test */
    public function it_validates_phone_number()
    {
        $this->expectException(\Rstacode\Otpiq\Exceptions\OtpiqException::class);
        $this->otpiq->send('123', 'verification');
    }

    /** @test */
    public function it_handles_invalid_api_key()
    {
        $otpiq = new Otpiq([
            'api_key' => 'invalid_key',
        ]);

        $this->expectException(\Rstacode\Otpiq\Exceptions\OtpiqException::class);
        $otpiq->getProjectInfo();
    }

    /** @test */
    public function it_requires_custom_message_for_custom_sms()
    {
        $this->expectException(\Rstacode\Otpiq\Exceptions\OtpiqException::class);

        $this->otpiq->send(
            $this->phoneNumber,
            'custom',
            ['senderId' => 'TestApp']// Missing customMessage
        );
    }
}
