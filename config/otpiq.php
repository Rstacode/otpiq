<?php
/**
 * Otpiq Configuration File
 * فایلی ڕێکخستنەکانی Otpiq
 *
 * This file contains all the configuration options for the Otpiq package
 * ئەم فایلە هەموو بژاردەکانی ڕێکخستن لەخۆدەگرێت بۆ پاکێجی Otpiq
 */

return [
    /*
   |--------------------------------------------------------------------------
   | Otpiq API Key
   | کلیلی API ی Otpiq
   |--------------------------------------------------------------------------
   |
   | Here you may specify your Otpiq API key. This will be used to authenticate
   | with the Otpiq API - you can find your API key on your Otpiq dashboard.
   |
   | لێرەدا دەتوانیت کلیلی API ی Otpiq دیاری بکەیت. ئەمە بەکاردێت بۆ
   | سەلماندن لەگەڵ API ی Otpiq - دەتوانیت کلیلی API لە داشبۆردی Otpiq بدۆزیتەوە.
   |
   */
    'api_key'          => env('OTPIQ_API_KEY', ''),

    /*
   |--------------------------------------------------------------------------
   | Default Provider
   | دابینکەری بنەڕەتی
   |--------------------------------------------------------------------------
   |
   | This option controls the default provider that will be used to send
   | messages when no specific provider is requested.
   |
   | Supported providers:
   | دابینکەرە پشتگیریکراوەکان:
   | - "auto": Automatically choose the best provider
   |           خۆکارانە باشترین دابینکەر هەڵدەبژێرێت
   | - "sms": Use SMS provider only
   |          تەنها دابینکەری SMS بەکاربهێنە
   | - "whatsapp": Use WhatsApp provider only
   |               تەنها دابینکەری WhatsApp بەکاربهێنە
   | - "telegram": Use Telegram provider only
   |               تەنها دابینکەری Telegram بەکاربهێنە
   |
   */
    'default_provider' => env('OTPIQ_PROVIDER', 'auto'),
];
