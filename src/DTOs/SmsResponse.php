<?php
/**
 * Data Transfer Object for SMS Response
 * بابەتی گواستنەوەی داتا بۆ وەڵامی SMS
 *
 * This DTO handles the response from the /sms endpoint when sending messages
 * ئەم DTO ـە وەڵامی endpoint ی /sms بەڕێوە دەبات کاتی ناردنی نامەکان
 *
 * Example Response / نموونەی وەڵام:
 * {
 *   "message": "SMS task created successfully",
 *   "smsId": "sms-1234567890",
 *   "remainingCredit": 920,
 *   "provider": "telegram",
 *   "status": "pending"
 * }
 *
 * @package Rstacode\Otpiq\DTOs
 */
namespace Rstacode\Otpiq\DTOs;

class SmsResponse
{
    /**
     * Response message from the API
     * پەیامی وەڵام لە API
     *
     * @var string
     */
    public string $message;

    /**
     * Unique identifier for the SMS
     * ناسێنەری تایبەت بۆ SMS
     *
     * @var string
     */
    public string $smsId;

    /**
     * Remaining credit balance after sending
     * باڵانسی کرێدیتی ماوە دوای ناردن
     *
     * @var int
     */
    public int $remainingCredit;

    /**
     * Provider used for sending (sms, whatsapp, telegram)
     * دابینکەری بەکارهاتوو بۆ ناردن
     *
     * @var string
     */
    public string $provider;

    /**
     * Current status of the SMS
     * دۆخی ئێستای SMS
     *
     * @var string
     */
    public string $status;

    /**
     * Create a new SmsResponse instance
     * دروستکردنی نموونەیەکی نوێی SmsResponse
     *
     * @param array $data Raw API response / وەڵامی خاوی API
     */
    public function __construct(array $data)
    {
        $this->message         = $data['message'];
        $this->smsId           = $data['smsId'];
        $this->remainingCredit = $data['remainingCredit'];
        $this->provider        = $data['provider'];
        $this->status          = $data['status'];
    }
}
