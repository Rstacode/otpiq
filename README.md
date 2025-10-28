# OTPIQ Laravel Package

<p align="center">
<a href="https://packagist.org/packages/rstacode/otpiq"><img src="https://img.shields.io/packagist/v/rstacode/otpiq.svg?style=flat-square" alt="Latest Version on Packagist"></a>
<a href="https://packagist.org/packages/rstacode/otpiq"><img src="https://img.shields.io/packagist/dt/rstacode/otpiq.svg?style=flat-square" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/rstacode/otpiq"><img src="https://img.shields.io/packagist/l/rstacode/otpiq.svg?style=flat-square" alt="License"></a>
</p>

The most reliable SMS, WhatsApp, and Telegram verification platform for your business in Iraq and Kurdistan.

OTPIQ provides a simple and powerful Laravel package to send verification codes and custom messages through multiple channels including SMS, WhatsApp, and Telegram with automatic fallback support.

## Features

- 🚀 **Multiple Channels**: SMS, WhatsApp, Telegram with automatic fallback
- 🔐 **Verification Codes**: Send OTP codes with ease
- 💬 **Custom Messages**: Send transactional and marketing messages
- 📱 **WhatsApp Templates**: Support for WhatsApp Business templates
- 🔄 **Auto Fallback**: Automatic channel switching for delivery guarantee
- 📊 **Delivery Tracking**: Real-time SMS delivery status tracking
- 🎯 **Custom Sender IDs**: Use your own branded sender IDs
- ⚡ **Fast & Reliable**: Optimized for performance
- 🛡️ **Exception Handling**: Comprehensive error handling

## Requirements

- PHP 8.1, 8.2, 8.3, or 8.4
- Laravel 10, 11, or 12

## Installation

Install the package via Composer:

```bash
composer require rstacode/otpiq
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=otpiq-config
```

Add your OTPIQ API key to your `.env` file:

```env
OTPIQ_API_KEY=sk_live_your_api_key_here
OTPIQ_BASE_URL=https://api.otpiq.com/api/
OTPIQ_TIMEOUT=30
```

You can get your API key from the [OTPIQ Dashboard](https://app.otpiq.com).

## Usage

### Get Project Information

Retrieve your project details and remaining credits:

```php
use Rstacode\Otpiq\Facades\Otpiq;

$info = Otpiq::getProjectInfo();

echo $info['projectName'];
echo $info['credit'];
```

**Response:**

```php
[
    'projectName' => 'My SMS Project',
    'credit' => 15000
]
```

### Send Verification Code

Send a verification code to a phone number:

```php
use Rstacode\Otpiq\Facades\Otpiq;

$response = Otpiq::sendSms([
    'phoneNumber' => '964750123456',
    'smsType' => 'verification',
    'verificationCode' => '123456',
    'provider' => 'whatsapp-sms',
]);

echo $response['smsId'];
echo $response['remainingCredit'];
echo $response['cost'];
```

**Response:**

```php
[
    'message' => 'SMS task created successfully',
    'smsId' => 'sms-1234567890abcdef123456',
    'remainingCredit' => 14800,
    'cost' => 200,
    'canCover' => true,
    'paymentType' => 'prepaid'
]
```

#### With Custom Sender ID

```php
$response = Otpiq::sendSms([
    'phoneNumber' => '964750123456',
    'smsType' => 'verification',
    'verificationCode' => '123456',
    'senderId' => 'MyBrand',
    'provider' => 'sms',
]);
```

#### With Delivery Report Webhook

```php
$response = Otpiq::sendSms([
    'phoneNumber' => '964750123456',
    'smsType' => 'verification',
    'verificationCode' => '123456',
    'deliveryReport' => [
        'webhookUrl' => 'https://your-app.com/webhooks/sms-status',
        'deliveryReportType' => 'all',
        'webhookSecret' => 'your_webhook_secret_123',
    ],
]);
```

### Send Custom Message

Send a custom transactional or marketing message:

```php
use Rstacode\Otpiq\Facades\Otpiq;

$response = Otpiq::sendSms([
    'phoneNumber' => '964750123456',
    'smsType' => 'custom',
    'customMessage' => 'Your order #12345 has been confirmed. Thank you!',
    'senderId' => 'MyShop',
    'provider' => 'sms',
]);
```

### Send WhatsApp Template Message

Send a message using a pre-approved WhatsApp template:

```php
use Rstacode\Otpiq\Facades\Otpiq;

$response = Otpiq::sendSms([
    'phoneNumber' => '964750123456',
    'smsType' => 'whatsapp-template',
    'templateName' => 'auth_template',
    'whatsappAccountId' => '68c46fecc509cdcec8fb3ef2',
    'whatsappPhoneId' => '68da31fb518ac3db3eb0a0f4',
    'templateParameters' => [
        'body' => [
            '1' => '123456',
            '2' => '10',
        ],
    ],
    'provider' => 'whatsapp',
]);
```

### Track SMS Status

Track the delivery status of a sent message:

```php
use Rstacode\Otpiq\Facades\Otpiq;

$status = Otpiq::trackSms('sms-1234567890abcdef123456');

echo $status['status'];
echo $status['lastChannel'];
```

**Response:**

```php
[
    'smsId' => 'sms-1234567890abcdef123456',
    'phoneNumber' => '964750123456',
    'status' => 'delivered',
    'cost' => 200,
    'isFinalStatus' => true,
    'lastChannel' => 'whatsapp',
    'channelFlow' => [
        [
            'channel' => 'whatsapp',
            'tried' => true,
            'success' => true,
        ],
    ]
]
```

### Get Sender IDs

Retrieve all your available sender IDs:

```php
use Rstacode\Otpiq\Facades\Otpiq;

$senderIds = Otpiq::getSenderIds();

foreach ($senderIds['data'] as $sender) {
    echo $sender['senderId'];
    echo $sender['status'];
    echo $sender['pricePerSms']['korekTelecom'];
}
```

**Response:**

```php
[
    'success' => true,
    'data' => [
        [
            '_id' => '507f1f77bcf86cd799439011',
            'senderId' => 'OTPIQ',
            'status' => 'accepted',
            'pricePerSms' => [
                'korekTelecom' => 80,
                'asiaCell' => 80,
                'zainIraq' => 80,
                'others' => 100,
            ]
        ]
    ]
]
```

## Provider Options

The `provider` parameter allows you to choose how your message is delivered:

- `auto` - Automatic channel selection (default)
- `whatsapp-sms` - Try WhatsApp first, fallback to SMS
- `telegram-sms` - Try Telegram first, fallback to SMS
- `whatsapp-telegram-sms` - Try WhatsApp, then Telegram, then SMS
- `sms` - SMS only
- `whatsapp` - WhatsApp only
- `telegram` - Telegram only

## Error Handling

The package provides comprehensive error handling through the `OtpiqApiException` class:

```php
use Rstacode\Otpiq\Facades\Otpiq;
use Rstacode\Otpiq\Exceptions\OtpiqApiException;

try {
    $response = Otpiq::sendSms([
        'phoneNumber' => '964750123456',
        'smsType' => 'verification',
        'verificationCode' => '123456',
    ]);
} catch (OtpiqApiException $e) {
    if ($e->isAuthError()) {
        echo 'Invalid API key';
    }

    if ($e->isCreditError()) {
        echo 'Insufficient credit: ' . $e->getRemainingCredit();
        echo 'Required credit: ' . $e->getRequiredCredit();
    }

    if ($e->isRateLimitError()) {
        echo 'Rate limit exceeded. Wait ' . $e->getRateLimitWaitMinutes() . ' minutes';
    }

    if ($e->isTrialModeError()) {
        echo 'Account in trial mode';
    }

    if ($e->isSpendingThresholdError()) {
        echo 'Spending threshold exceeded';
    }

    if ($e->isSenderIdError()) {
        echo 'Sender ID not found';
    }

    if ($e->isValidationError()) {
        echo 'Validation error: ' . $e->getFirstError();
    }

    echo $e->getMessage();
    print_r($e->getResponseData());
}
```

### Available Exception Methods

- `isAuthError()` - Check if error is authentication related
- `isCreditError()` - Check if error is due to insufficient credit
- `isRateLimitError()` - Check if rate limit was exceeded
- `isTrialModeError()` - Check if account is in trial mode
- `isSpendingThresholdError()` - Check if spending threshold was exceeded
- `isSenderIdError()` - Check if sender ID is invalid
- `isValidationError()` - Check if error is validation related
- `getRemainingCredit()` - Get remaining credit balance
- `getRequiredCredit()` - Get required credit for the request
- `getRateLimitWaitMinutes()` - Get wait time in minutes
- `getFirstError()` - Get first validation error message
- `getResponseData()` - Get full API response data

## Testing

You can use the OTPIQ dashboard to test your integration:

1. Visit the [OTPIQ Dashboard](https://app.otpiq.com)
2. Navigate to **Messaging** → **Send SMS**
3. Build and test your API calls interactively

## API Reference

### sendSms(array $data): array

Send an SMS message.

**Parameters:**

#### Verification Message

```php
[
    'phoneNumber' => 'string (required)',
    'smsType' => 'verification',
    'verificationCode' => 'string (required)',
    'senderId' => 'string (optional)',
    'provider' => 'string (optional)',
    'whatsappAccountId' => 'string (optional)',
    'whatsappPhoneId' => 'string (optional)',
    'templateName' => 'string (optional)',
    'deliveryReport' => [
        'webhookUrl' => 'string',
        'deliveryReportType' => 'all|final',
        'webhookSecret' => 'string',
    ],
]
```

#### Custom Message

```php
[
    'phoneNumber' => 'string (required)',
    'smsType' => 'custom',
    'customMessage' => 'string (required)',
    'senderId' => 'string (optional)',
    'provider' => 'string (optional)',
    'whatsappAccountId' => 'string (optional)',
    'whatsappPhoneId' => 'string (optional)',
    'templateName' => 'string (optional)',
    'deliveryReport' => [
        'webhookUrl' => 'string',
        'deliveryReportType' => 'all|final',
        'webhookSecret' => 'string',
    ],
]
```

#### WhatsApp Template Message

```php
[
    'phoneNumber' => 'string (required)',
    'smsType' => 'whatsapp-template',
    'templateName' => 'string (required)',
    'whatsappAccountId' => 'string (required)',
    'whatsappPhoneId' => 'string (required)',
    'templateParameters' => [
        'body' => [
            '1' => 'string',
            '2' => 'string',
        ],
    ],
    'provider' => 'string (optional)',
    'deliveryReport' => [
        'webhookUrl' => 'string',
        'deliveryReportType' => 'all|final',
        'webhookSecret' => 'string',
    ],
]
```

### getProjectInfo(): array

Get project information and remaining credits.

**Returns:**

```php
[
    'projectName' => 'string',
    'credit' => 'integer'
]
```

### trackSms(string $smsId): array

Track SMS delivery status.

**Parameters:**

- `$smsId` - The SMS ID returned from sendSms()

**Returns:**

```php
[
    'smsId' => 'string',
    'phoneNumber' => 'string',
    'status' => 'string',
    'cost' => 'integer',
    'isFinalStatus' => 'boolean',
    'lastChannel' => 'string',
    'channelFlow' => 'array'
]
```

### getSenderIds(): array

Get all available sender IDs.

**Returns:**

```php
[
    'success' => 'boolean',
    'data' => [
        [
            '_id' => 'string',
            'senderId' => 'string',
            'status' => 'string',
            'pricePerSms' => [
                'korekTelecom' => 'integer',
                'asiaCell' => 'integer',
                'zainIraq' => 'integer',
                'others' => 'integer',
            ]
        ]
    ]
]
```

## Support

- **Email**: rstacode@gmail.com
- **Issues**: [GitHub Issues](https://github.com/rstacode/otpiq/issues)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- [Rstacode](https://github.com/rstacode)
- [All Contributors](https://github.com/rstacode/otpiq/contributors)
