<?php
/**
 * Data Transfer Object for SMS Tracking Information
 * بابەتی گواستنەوەی داتا بۆ زانیاری بەدواداچوونی SMS
 *
 * This DTO handles the response from the /sms/track/:smsId endpoint
 * ئەم DTO ـە وەڵامی endpoint ی /sms/track/:smsId بەڕێوە دەبات
 *
 * Example Response / نموونەی وەڵام:
 * {
 *   "status": "delivered",
 *   "phoneNumber": "9647501234567",
 *   "smsId": "sms-1234567890",
 *   "cost": 80
 * }
 *
 * @package Rstacode\Otpiq\DTOs
 */
namespace Rstacode\Otpiq\DTOs;

class TrackingInfo
{
    /**
     * Current status of the SMS (delivered, pending, failed)
     * دۆخی ئێستای SMS (گەیشتووە، چاوەڕوانە، سەرکەوتوو نەبوو)
     *
     * @var string
     */
    public string $status;

    /**
     * Recipient's phone number
     * ژمارەی مۆبایلی وەرگر
     *
     * @var string
     */
    public string $phoneNumber;

    /**
     * Unique identifier for the SMS
     * ناسێنەری تایبەت بۆ SMS
     *
     * @var string
     */
    public string $smsId;

    /**
     * Cost of the SMS in credits
     * تێچووی SMS بە کرێدیت
     *
     * @var int
     */
    public int $cost;

    /**
     * Create a new TrackingInfo instance
     * دروستکردنی نموونەیەکی نوێی TrackingInfo
     *
     * @param array $data Raw API response / وەڵامی خاوی API
     */
    public function __construct(array $data)
    {
        $this->status      = $data['status'];
        $this->phoneNumber = $data['phoneNumber'];
        $this->smsId       = $data['smsId'];
        $this->cost        = $data['cost'];
    }
}
