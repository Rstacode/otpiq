# OTPIQ Laravel Package

A Laravel package for seamless integration with the OTPIQ SMS service API. Send verification codes and custom messages via SMS, WhatsApp, or Telegram with ease.

---

## 🔗 Quick Links

- **[Official Website](https://otpiq.com/)**

---

## ✨ Features

- **Multi-Channel Messaging**: Send messages via SMS, WhatsApp, or Telegram.
- **Verification Codes**: Send OTP verification codes easily.
- **Custom Messages**: Send personalized messages with approved sender IDs.
- **Delivery Tracking**: Track the status of sent messages with detailed channel flow.
- **Credit Management**: Monitor your remaining credits and usage.
- **Sender ID Management**: Retrieve and manage your approved sender IDs.
- **Webhooks Support**: Real-time notifications for message status updates.
- **Error Handling**: Comprehensive exception handling.
- **Laravel 10-12 Support**: Fully compatible with Laravel 10, 11, and 12.
- **PHP 8.1-8.4 Support**: Built for modern PHP applications.

---

## 🛠️ Requirements

- PHP 8.1, 8.2, 8.3, or 8.4
- Laravel 10, 11, or 12
- Guzzle HTTP 7.0+

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
    'phoneNumber' => '964750123456',
    'smsType' => 'verification',
    'verificationCode' => '123456',
    'provider' => 'whatsapp-telegram-sms' // Optional (recommended)
]);

// Response:
// [
//     'message' => 'SMS task created successfully',
//     'smsId' => 'sms-1234567890abcdef123456',
//     'remainingCredit' => 14800,
//     'cost' => 200,
//     'canCover' => true,
//     'paymentType' => 'prepaid'
// ]
```

### Send Custom Message

```php
$response = Otpiq::sendSms([
    'phoneNumber' => '964750123456',
    'smsType' => 'custom',
    'customMessage' => 'Special offer! 20% discount today!',
    'senderId' => 'OTPIQ',
    'provider' => 'sms' // Required for custom messages
]);

// Response: Same as verification code response above
```

### Track SMS Status

```php
$status = Otpiq::trackSms('sms-1234567890');

// Response:
// [
//     'smsId' => 'sms-1234567890abcdef123456',
//     'phoneNumber' => '964750123456',
//     'status' => 'sent',
//     'cost' => 200,
//     'isFinalStatus' => false,
//     'lastChannel' => 'whatsapp',
//     'channelFlow' => [
//         [
//             'channel' => 'whatsapp',
//             'tried' => true,
//             'success' => true
//         ],
//         [
//             'channel' => 'sms',
//             'tried' => false
//         ]
//     ]
// ]
```

### Get Project Information

```php
$projectInfo = Otpiq::getProjectInfo();

// Response:
// [
//     'projectName' => 'My SMS Project',
//     'credit' => 15000
// ]

// Access project info:
echo $projectInfo['projectName']; // "My SMS Project"
echo $projectInfo['credit']; // 15000
```

### Get Sender IDs

```php
$senderIds = Otpiq::getSenderIds();

// Response:
// [
//     'success' => true,
//     'data' => [
//         [
//             '_id' => '507f1f77bcf86cd799439011',
//             'senderId' => 'OTPIQ',
//             'status' => 'accepted',
//             'pricePerSms' => [
//                 'korekTelecom' => 80,
//                 'asiaCell' => 80,
//                 'zainIraq' => 80,
//                 'others' => 100
//             ]
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

OTPIQ offers 6 provider options including smart fallback routes:

- `whatsapp-telegram-sms` (recommended): Try WhatsApp → Telegram → SMS (maximum delivery success)
- `whatsapp-sms`: Try WhatsApp first, fallback to SMS
- `telegram-sms`: Try Telegram first, fallback to SMS
- `sms`: SMS only
- `whatsapp`: WhatsApp only
- `telegram`: Telegram only

**Note**: For custom messages, the provider is typically set to `sms` since sender IDs are mainly supported via SMS.

---

## 🔗 Webhooks

OTPIQ provides real-time delivery status notifications via webhooks. When you configure webhooks, you'll receive instant updates about message delivery status directly to your server.

### How to Configure Webhooks

Include a `deliveryReport` object in your SMS request:

```php
$response = Otpiq::sendSms([
    'phoneNumber' => '964750123456',
    'smsType' => 'verification',
    'verificationCode' => '123456',
    'deliveryReport' => [
        'webhookUrl' => 'https://your-app.com/webhooks/sms-status',
        'deliveryReportType' => 'all', // 'all' or 'final'
        'webhookSecret' => 'your_secret_123' // Optional
    ]
]);
```

### Webhook Configuration Fields

| Field                | Type   | Required | Description                                              |
| -------------------- | ------ | -------- | -------------------------------------------------------- |
| `webhookUrl`         | string | Yes      | HTTPS URL where status updates are sent                  |
| `deliveryReportType` | string | No       | `"all"` for all updates, `"final"` for final status only |
| `webhookSecret`      | string | No       | Secret key for webhook authentication                    |

### Webhook Payload Structure

Each webhook request contains a JSON payload with these fields:

**Required Fields:**

- `smsId`: Unique message identifier
- `deliveryReportType`: Your configured report type
- `isFinal`: Whether this is the final status
- `channel`: Messaging channel (sms, whatsapp, telegram)
- `status`: Delivery status (sent, delivered, failed)

**Optional Fields:**

- `reason`: Failure reason (only when status is 'failed')
- `senderId`: Sender ID used (only for SMS with custom sender IDs)

### Delivery Status Flow

**SMS Messages:**

- `sent` → Message accepted by carrier
- `delivered` → Message confirmed delivered to recipient
- `failed` → Message could not be delivered

**WhatsApp Messages:**

- `sent` → Message sent to WhatsApp servers
- `delivered` → Message delivered to recipient's device
- `failed` → Message could not be sent or delivered

**Telegram Messages:**

- `sent` → Message sent to Telegram servers
- `delivered` → Message delivered to recipient
- `failed` → Message could not be sent

### Webhook Examples

#### Example 1: SMS with Custom Sender ID

**Request:**

```php
$response = Otpiq::sendSms([
    'phoneNumber' => '964750123456',
    'smsType' => 'custom',
    'customMessage' => 'Your order has been confirmed!',
    'senderId' => 'OTPIQ',
    'deliveryReport' => [
        'webhookUrl' => 'https://your-app.com/webhooks/sms-status',
        'deliveryReportType' => 'all',
        'webhookSecret' => 'your_secret_123'
    ]
]);
```

**Webhook Payloads Received:**

Sent Status:

```json
{
  "smsId": "sms_1234567890abcdef",
  "deliveryReportType": "all",
  "isFinal": false,
  "channel": "sms",
  "status": "sent",
  "senderId": "OTPIQ"
}
```

Delivered Status:

```json
{
  "smsId": "sms_1234567890abcdef",
  "deliveryReportType": "all",
  "isFinal": true,
  "channel": "sms",
  "status": "delivered",
  "senderId": "OTPIQ"
}
```

#### Example 2: WhatsApp with Final-Only Reports

**Request:**

```php
$response = Otpiq::sendSms([
    'phoneNumber' => '964750123456',
    'smsType' => 'verification',
    'verificationCode' => '123456',
    'provider' => 'whatsapp',
    'deliveryReport' => [
        'webhookUrl' => 'https://your-app.com/webhooks/whatsapp-status',
        'deliveryReportType' => 'final'
    ]
]);
```

**Webhook Payload (Final Status Only):**

```json
{
  "smsId": "sms_1234567890abcdef",
  "deliveryReportType": "final",
  "isFinal": true,
  "channel": "whatsapp",
  "status": "delivered"
}
```

#### Example 3: Failed Message

**Request:**

```php
$response = Otpiq::sendSms([
    'phoneNumber' => '964750123456',
    'smsType' => 'custom',
    'customMessage' => 'Your order has been confirmed!',
    'senderId' => 'OTPIQ',
    'deliveryReport' => [
        'webhookUrl' => 'https://your-app.com/webhooks/sms-status',
        'deliveryReportType' => 'final'
    ]
]);
```

**Webhook Payload (Failure):**

```json
{
  "smsId": "sms_abcdef1234567890",
  "deliveryReportType": "final",
  "isFinal": true,
  "channel": "sms",
  "status": "failed",
  "reason": "Carrier rejected the message",
  "senderId": "OTPIQ"
}
```

### Laravel Event Integration

For handling webhook events in Laravel:

```php
// In routes/web.php
Route::post('/webhooks/sms-status', function (Request $request) {
    $payload = $request->all();

    // Verify webhook signature (for security)
    // $signature = $request->header('X-OTPIQ-Signature');

    switch ($payload['status']) {
        case 'delivered':
            // Handle delivered event
            Log::info("SMS {$payload['smsId']} delivered via {$payload['channel']}");
            break;

        case 'failed':
            // Handle failed event
            Log::error("SMS {$payload['smsId']} failed: {$payload['reason']}");
            break;

        case 'sent':
            // Handle sent event
            Log::info("SMS {$payload['smsId']} sent via {$payload['channel']}");
            break;
    }

    return response()->json(['status' => 'ok']);
});
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

Thank you for considering contributing to the OTPIQ Laravel package!

1. Fork the repository.
2. Create your feature branch (`git checkout -b feature/amazing-feature`).
3. Commit your changes (`git commit -m 'Add some amazing feature'`).
4. Push to the branch (`git push origin feature/amazing-feature`).
5. Open a Pull Request.

---

## 📞 Support

For support, email [rstacode@gmail.com](mailto:rstacode@gmail.com) or create an issue in the [GitHub repository](https://github.com/rstacode/otpiq/issues).
