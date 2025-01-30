<?php
/**
 * Otpiq Facade - Provides a static interface to the Otpiq service
 * Facade ی Otpiq - ڕووکارێکی static دابین دەکات بۆ خزمەتگوزاری Otpiq
 *
 * This facade allows you to use Otpiq methods statically:
 * ئەم facade ـە ڕێگەت پێدەدات میسۆدەکانی Otpiq بە شێوەی static بەکاربهێنیت:
 *
 * Example/نموونە:
 * Otpiq::send('9647501234567', 'verification');
 *
 * @package Rstacode\Otpiq\Facades
 */
namespace Rstacode\Otpiq\Facades;

use Illuminate\Support\Facades\Facade;

class Otpiq extends Facade
{
    /**
     * Get the registered name of the component
     * وەرگرتنی ناوی تۆمارکراوی کۆمپۆنێنتەکە
     *
     * Returns the service container binding key
     * کلیلی بەستنەوەی service container دەگەڕێنێتەوە
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'otpiq';
    }
}
