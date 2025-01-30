<?php
/**
 * Data Transfer Object for Sender ID Information
 * بابەتی گواستنەوەی داتا بۆ زانیاری ناردەر ID
 *
 * This DTO represents a single sender ID entry from the /sender-ids endpoint
 * ئەم DTO ـە نوێنەرایەتی تۆمارێکی تاکی sender ID دەکات لە endpoint ی /sender-ids
 *
 * Example Response / نموونەی وەڵام:
 * {
 *   "id": "sender-123",
 *   "senderId": "MyBrand",
 *   "status": "accepted",
 *   "createdAt": "2024-01-01T00:00:00.000Z"
 * }
 *
 * @package Rstacode\Otpiq\DTOs
 */
namespace Rstacode\Otpiq\DTOs;

class SenderId
{
    /**
     * Unique identifier for the sender ID
     * ناسێنەری تایبەت بۆ sender ID
     *
     * @var string
     */
    public string $id;

    /**
     * The actual sender ID text
     * دەقی sender ID
     *
     * @var string
     */
    public string $senderId;

    /**
     * Status of the sender ID (e.g., accepted, pending)
     * دۆخی sender ID (بۆ نموونە: قبوڵکراو، چاوەڕوان)
     *
     * @var string
     */
    public string $status;

    /**
     * Creation timestamp
     * کاتی دروستکردن
     *
     * @var string
     */
    public string $createdAt;

    /**
     * Create a new SenderId instance
     * دروستکردنی نموونەیەکی نوێی SenderId
     *
     * @param array $data Raw API response / وەڵامی خاوی API
     */
    public function __construct(array $data)
    {
        $this->id        = $data['id'];
        $this->senderId  = $data['senderId'];
        $this->status    = $data['status'];
        $this->createdAt = $data['createdAt'];
    }
}
