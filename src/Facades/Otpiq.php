<?php
namespace Rstacode\Otpiq\Facades;

use Illuminate\Support\Facades\Facade;
use Rstacode\Otpiq\DTOs\ProjectInfo;
use Rstacode\Otpiq\DTOs\SmsData;

/**
 * @method static ProjectInfo getProjectInfo()
 * @method static array sendSms(SmsData $smsData)
 * @method static array getSenderIds()
 * @method static array trackSms(string $smsId)
 *
 * @see \Rstacode\Otpiq\Services\OtpiqService
 */
class Otpiq extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'otpiq';
    }
}
