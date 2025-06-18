<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .otp-container {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #2563eb;
            letter-spacing: 4px;
            margin: 20px 0;
        }
        .note {
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Hello {{ $firstName }},</h2>

    <p>Thank you for registering your property. To complete your registration, please use the following verification code:</p>

    <div class="otp-container">
        <div class="otp-code">{{ $otp }}</div>
    </div>

    <p>This code will expire in 10 minutes for security purposes.</p>

    <p class="note">If you didn't request this code, please ignore this email.</p>
</body>
</html>
