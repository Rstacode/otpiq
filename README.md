![Image](https://github.com/user-attachments/assets/6bae8406-3f7b-4dc9-bbaf-279f2be0b63e)

# OTPIQ Laravel Package

![Packagist License](https://img.shields.io/packagist/l/rstacode/otpiq)
![Packagist Version](https://img.shields.io/packagist/v/rstacode/otpiq)
![PHP Version](https://img.shields.io/packagist/php-v/rstacode/otpiq)
![Laravel Version](https://img.shields.io/badge/Laravel-10.x-red.svg)

A Laravel package for seamless integration with the OTPIQ SMS service API. Send verification codes and custom messages via SMS, WhatsApp, or Telegram with ease.

---

## 🔗 Quick Links

- **[Official Website](https://otpiq.com/)**
- **[Live Demo](https://otpiq.rstacode.dev)**
- **[Demo Source Code](https://github.com/Rstacode/otpiq_demo)**

---

## ✨ Features

- **Multi-Channel Messaging**: Send messages via SMS, WhatsApp, or Telegram.
- **Verification Codes**: Easily send OTPs and verification codes.
- **Custom Messages**: Send personalized messages with approved sender IDs.
- **Delivery Tracking**: Track the status of sent messages in real-time.
- **Credit Management**: Monitor your remaining credits and usage.
- **Sender ID Management**: Retrieve and manage your approved sender IDs.
- **Laravel 10+ Support**: Fully compatible with Laravel 10 and above.
- **PHP 8.1+ Support**: Built for modern PHP applications.

---

## 🛠️ Requirements

- PHP 8.1 or higher
- Laravel 10.x or higher
- Composer

---

## 🚀 Installation

1. Install the package via Composer:

   ```bash
   composer require rstacode/otpiq
   ```

2. Publish the configuration file:

   ```bash
   php artisan vendor:publish --provider="Rstacode\Otpiq\OtpiqServiceProvider" --tag="otpiq-config"
   ```

3. Add your OTPIQ API key to your `.env` file:
   ```env
   OTPIQ_API_KEY=your_api_key_here
   ```

---

## ⚙️ Configuration

The configuration file (`config/otpiq.php`) includes the following options:

```php
return [
    'api_key' => env('OTPIQ_API_KEY', ''),
    'base_url' => env('OTPIQ_BASE_URL', 'https://api.otpiq.com/api/'),
];
```

---

## 🎯 Usage

### Send Verification Code

```php
use Rstacode\Otpiq\Facades\Otpiq;

$response = Otpiq::sendSms([
    'phoneNumber' => '9647501234567',
    'smsType' => 'verification',
    'verificationCode' => '123456',
    'provider' => 'auto' // Optional (default: auto)
]);

// Response:
// [
//     'message' => 'SMS task created successfully',
//     'smsId' => 'sms-1234567890',
//     'remainingCredit' => 920,
//     'provider' => 'telegram',
//     'status' => 'pending'
// ]
```

### Send Custom Message

```php
$response = Otpiq::sendSms([
    'phoneNumber' => '9647501234567',
    'smsType' => 'custom',
    'customMessage' => 'Special offer! 20% discount today!',
    'senderId' => 'MyStore',
    'provider' => 'sms' // Required for custom messages
]);
```

### Track SMS Status

```php
$status = Otpiq::trackSms('sms-1234567890');

// Response:
// [
//     'status' => 'delivered',
//     'phoneNumber' => '9647501234567',
//     'smsId' => 'sms-1234567890',
//     'cost' => 80
// ]
```

### Get Project Information

```php
$projectInfo = Otpiq::getProjectInfo();

// Access project info:
echo $projectInfo['projectName']; // "My Project"
echo $projectInfo['credit']; // 1000
```

### Get Sender IDs

```php
$senderIds = Otpiq::getSenderIds();

// Response:
// [
//     'senderIds' => [
//         [
//             'id' => 'sender-123',
//             'senderId' => 'MyBrand',
//             'status' => 'accepted',
//             'createdAt' => '2024-01-01T00:00:00.000Z'
//         ]
//     ]
// ]
```

---

## 🚨 Error Handling

The package provides comprehensive error handling. Here's how to handle errors:

```php
use Rstacode\Otpiq\Exceptions\OtpiqApiException;

try {
    $response = Otpiq::sendSms([...]);
} catch (OtpiqApiException $e) {
    // Handle API errors
    logger()->error('OTPIQ Error: ' . $e->getMessage());

    // Access detailed errors
    if ($e->hasErrors()) {
        $errors = $e->getErrors();
    }

    // Check for insufficient credit
    if ($e->isCreditError()) {
        // Handle low credit
    }
}
```

---

## 🔌 Available Providers

When sending messages, you can specify the provider:

- `auto` (recommended): System automatically chooses the best available provider.
- `sms`: Send via SMS.
- `whatsapp`: Send via WhatsApp.
- `telegram`: Send via Telegram.

**Note**: When `smsType` is `custom`, the provider is automatically set to `sms`.

---

## 🧪 Testing

Run the test suite:

```bash
composer test
```

Run specific tests:

```bash
./vendor/bin/phpunit tests/Unit/OtpiqServiceTest.php
```

Mock API responses in tests:

```php
Otpiq::fake([
    'info' => ['projectName' => 'Test Project', 'credit' => 5000],
    'sms' => ['smsId' => 'test-123', 'status' => 'queued']
]);
```

---

## 📜 License

This package is licensed under the **MIT License**. See the [LICENSE](LICENSE.md) file for details.

---

## 🔒 Security

If you discover any security-related issues, please email [rstacode@gmail.com](mailto:rstacode@gmail.com) instead of using the issue tracker.

---

## 💡 Credits

- **[Rstacode](https://github.com/rstacode)**
- **[All Contributors](../../contributors)**

---

## 🤝 Contributing

Thank you for considering contributing to the OTPIQ Laravel package! Please review our [contribution guidelines](CONTRIBUTING.md) before submitting a pull request.

1. Fork the repository.
2. Create your feature branch (`git checkout -b feature/amazing-feature`).
3. Commit your changes (`git commit -m 'Add some amazing feature'`).
4. Push to the branch (`git push origin feature/amazing-feature`).
5. Open a Pull Request.

---

## 📞 Support

For support, email [rstacode@gmail.com](mailto:rstacode@gmail.com) or create an issue in the [GitHub repository](https://github.com/rstacode/otpiq/issues).
