<?php
/**
 * Data Transfer Object for Collection of Sender IDs
 * بابەتی گواستنەوەی داتا بۆ کۆمەڵێک Sender ID
 *
 * This DTO handles the response from the /sender-ids endpoint, which returns multiple sender IDs
 * ئەم DTO ـە وەڵامی endpoint ی /sender-ids بەڕێوە دەبات، کە چەندین sender ID دەگەڕێنێتەوە
 *
 * Example Response / نموونەی وەڵام:
 * {
 *   "senderIds": [
 *     {
 *       "id": "sender-123",
 *       "senderId": "MyBrand",
 *       "status": "accepted",
 *       "createdAt": "2024-01-01T00:00:00.000Z"
 *     },
 *     {
 *       "id": "sender-124",
 *       "senderId": "MyCompany",
 *       "status": "pending",
 *       "createdAt": "2024-01-02T00:00:00.000Z"
 *     }
 *   ]
 * }
 *
 * @package Rstacode\Otpiq\DTOs
 */
namespace Rstacode\Otpiq\DTOs;

class SenderIdCollection
{
    /**
     * Array of SenderId objects
     * ئەڕەیەک لە ئۆبجێکتەکانی SenderId
     *
     * @var SenderId[]
     */
    public array $items;

    /**
     * Create a new SenderIdCollection instance
     * دروستکردنی نموونەیەکی نوێی SenderIdCollection
     *
     * @param array $data Array of sender ID data / ئەڕەیەک لە داتای sender ID
     */
    public function __construct(array $data)
    {
        // Convert each item to SenderId object
        // گۆڕینی هەر ئایتەمێک بۆ ئۆبجێکتی SenderId
        $this->items = array_map(fn($item) => new SenderId($item), $data);
    }
}
