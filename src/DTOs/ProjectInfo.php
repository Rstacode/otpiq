<?php
/**
 * Data Transfer Object for Project Information
 * بابەتی گواستنەوەی داتا بۆ زانیاری پڕۆژە
 *
 * This DTO handles the response from the /info endpoint
 * ئەم DTO ـە وەڵامی endpoint ی /info بەڕێوە دەبات
 *
 * Example Response / نموونەی وەڵام:
 * {
 *   "projectName": "My Project",
 *   "credit": 1000
 * }
 *
 * @package Rstacode\Otpiq\DTOs
 */
namespace Rstacode\Otpiq\DTOs;

class ProjectInfo
{
    /**
     * Name of the project
     * ناوی پڕۆژە
     *
     * @var string
     */
    public string $projectName;

    /**
     * Remaining credit balance
     * باڵانسی کرێدیتی ماوە
     *
     * @var int
     */
    public int $credit;

    /**
     * Create a new ProjectInfo instance
     * دروستکردنی نموونەیەکی نوێی ProjectInfo
     *
     * @param array $data Raw API response / وەڵامی خاوی API
     */
    public function __construct(array $data)
    {
        $this->projectName = $data['projectName'];
        $this->credit      = $data['credit'];
    }
}
