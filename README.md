![Image](https://github.com/user-attachments/assets/6bae8406-3f7b-4dc9-bbaf-279f2be0b63e)

# OTPIQ Laravel Package

![Packagist License](https://img.shields.io/packagist/l/rstacode/otpiq)
![Packagist Version](https://img.shields.io/packagist/v/rstacode/otpiq)
![PHP Version](https://img.shields.io/packagist/php-v/rstacode/otpiq)
![Laravel Version](https://img.shields.io/badge/Laravel-10.x-red.svg)

A Laravel package for seamless integration with the OTPIQ SMS service API. Send verification codes and custom messages via SMS, WhatsApp, or Telegram with ease.

## [- Official Website](https://otpiq.com/)

## [- Live Demo](https://otpiq.rstacode.dev)

## [- Source Code Live Demo](https://github.com/Rstacode/otpiq_demo)

## Features

[rest of the README remains the same...]

- Send verification codes via SMS, WhatsApp, or Telegram
- Send custom messages with approved sender IDs
- Track SMS delivery status
- Get project information and credits
- Manage sender IDs
- Full Laravel 10+ support
- PHP 8.1+ support

## Requirements

- PHP 8.1 or higher
- Laravel 10.x or higher
- Composer

## Installation

Install the package via Composer:

```bash
composer require rstacode/otpiq
```

After installing, publish the configuration file:

```bash
php artisan vendor:publish --provider="Rstacode\Otpiq\OtpiqServiceProvider" --tag="otpiq-config"
```

## Configuration

Add your OTPIQ API key to your `.env` file:

```env
OTPIQ_API_KEY=your_api_key_here
```

## Usage

### Send Verification Code

```php
use Rstacode\Otpiq\Facades\Otpiq;
use Rstacode\Otpiq\DTOs\SmsData;

// Send verification code
$response = Otpiq::sendSms(new SmsData(
    phoneNumber: '9647501234567',
    smsType: 'verification',
    verificationCode: '123456'
));

// Response
[
    'message' => 'SMS task created successfully',
    'smsId' => 'sms-1234567890',
    'remainingCredit' => 920,
    'provider' => 'telegram',
    'status' => 'pending'
]
```

### Send Custom Message

```php
// Send custom message with sender ID
$response = Otpiq::sendSms(new SmsData(
    phoneNumber: '9647501234567',
    smsType: 'custom',
    customMessage: 'Your custom message here',
    senderId: 'YourBrand'
));
```

### Track SMS Status

```php
// Track SMS delivery status
$status = Otpiq::trackSms('sms-1234567890');

// Response
[
    'status' => 'delivered',
    'phoneNumber' => '964750000000',
    'smsId' => 'sms-1234567890',
    'cost' => 80
]
```

### Get Project Information

```php
// Get project info and remaining credits
$projectInfo = Otpiq::getProjectInfo();

// Access project info
echo $projectInfo->projectName; // "My Project"
echo $projectInfo->credit; // 1000
```

### Get Sender IDs

```php
// Get all sender IDs
$senderIds = Otpiq::getSenderIds();

// Response
[
    'senderIds' => [
        [
            'id' => 'sender-123',
            'senderId' => 'MyBrand',
            'status' => 'accepted',
            'createdAt' => '2024-01-01T00:00:00.000Z'
        ]
    ]
]
```

## Error Handling

The package includes comprehensive error handling. Here's how to handle different scenarios:

```php
use Rstacode\Otpiq\Exceptions\OtpiqException;
use Rstacode\Otpiq\Exceptions\InvalidConfigurationException;

try {
    $response = Otpiq::sendSms($smsData);
} catch (InvalidConfigurationException $e) {
    // Handle invalid API key or configuration issues
    echo $e->getMessage();
} catch (OtpiqException $e) {
    // Handle API errors, insufficient credit, etc.
    echo $e->getMessage();
    $errors = $e->getErrors(); // Get detailed error information
}
```

## Available Providers

When sending messages, you can specify the provider:

- `auto` (recommended): System automatically chooses the best available provider
- `sms`: Send via SMS
- `whatsapp`: Send via WhatsApp
- `telegram`: Send via Telegram

Note: When `smsType` is `custom`, the provider is automatically set to `sms`.

## Testing

Run the test suite:

```bash
composer test
```

Or run specific tests:

```bash
./vendor/bin/phpunit tests/Unit/OtpiqTest.php
```

## License

This package is licensed under the MIT License. See the [LICENSE](LICENSE.md) file for details.

## Security

If you discover any security-related issues, please email [rstacode@gmail.com](mailto:rstacode@gmail.com) instead of using the issue tracker.

## Credits

- [Rstacode](https://github.com/rstacode)
- [All Contributors](../../contributors)

## Contributing

Thank you for considering contributing to the OTPIQ Laravel package! Please review our [contribution guidelines](CONTRIBUTING.md) before submitting a pull request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Support

For support, email [rstacode@gmail.com](mailto:rstacode@gmail.com) or create an issue in the GitHub repository.
