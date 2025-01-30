<?php
/**
 * Test Suite for Otpiq Package
 * کۆمەڵە تێستەکان بۆ پاکێجی Otpiq
 *
 * This class contains unit tests to verify the functionality of the Otpiq package
 * ئەم کڵاسە تێستە یەکەییەکان لەخۆدەگرێت بۆ پشتڕاستکردنەوەی کارکردنی پاکێجی Otpiq
 *
 * @package Rstacode\Otpiq\Tests
 */
namespace Rstacode\Otpiq\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Rstacode\Otpiq\Otpiq;

class OtpiqTest extends TestCase
{
    /**
     * Test getting project information
     * تێستی وەرگرتنی زانیاری پڕۆژە
     *
     * @test
     */
    public function it_can_get_project_info()
    {
        // Create mock response / دروستکردنی وەڵامی ساختە
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'projectName' => 'Test Project',
                'credit'      => 1000,
            ]))
        ]);

        // Create handler stack / دروستکردنی handler stack
        $handlerStack = HandlerStack::create($mock);

        // Create client with mock / دروستکردنی client لەگەڵ mock
        $client = new Client(['handler' => $handlerStack]);

        // Create Otpiq instance / دروستکردنی نموونەی Otpiq
        $otpiq = new Otpiq(['api_key' => 'test_key'], $client);

        // Test the response / تاقیکردنەوەی وەڵام
        $info = $otpiq->getProjectInfo();
        $this->assertEquals('Test Project', $info->projectName);
        $this->assertEquals(1000, $info->credit);
    }

    /**
     * Test sending verification SMS
     * تێستی ناردنی SMS ی سەلماندن
     *
     * @test
     */
    public function it_can_send_verification_sms()
    {
        // Create mock response / دروستکردنی وەڵامی ساختە
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'message' => 'SMS sent',
                'smsId'   => '123456',
                'status'  => 'pending',
            ]))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);
        $otpiq        = new Otpiq(['api_key' => 'test_key'], $client);

        $response = $otpiq->send('9647501234567', 'verification');

        $this->assertEquals('123456', $response->smsId);
        $this->assertEquals('pending', $response->status);
    }

    /**
     * Test phone number validation
     * تێستی پشتڕاستکردنەوەی ژمارەی مۆبایل
     *
     * @test
     */
    public function it_validates_phone_number()
    {
        $otpiq = new Otpiq(['api_key' => 'test_key']);

        $this->expectException(\Rstacode\Otpiq\Exceptions\OtpiqException::class);
        $otpiq->send('123'); // Should throw exception for invalid phone number
    }
}
