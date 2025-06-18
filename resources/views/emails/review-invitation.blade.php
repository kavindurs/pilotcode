<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Request from {{ $businessName }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #3b82f6;
            padding: 20px;
            color: white;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        img.pixel-tracker {
            width: 1px;
            height: 1px;
            border: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your Review Matters</h1>
        </div>
        <div class="content">
            <p>Hi {{ $customerName }},</p>

            <div>
                {!! nl2br(e($messageContent)) !!}
            </div>

            <div style="text-align: center;">
                <a href="{{ $reviewUrl }}" class="button">Leave Your Review</a>
            </div>

            <p style="margin-top: 30px; font-size: 14px; color: #777;">
                Thank you for your time and support!<br>
                The {{ $businessName }} Team
            </p>
        </div>
        <div class="footer">
            <p>This email was sent by {{ $businessName }}.</p>
            <p>If you received this by mistake, please disregard this email.</p>
        </div>
    </div>

    @if(isset($trackingUrl))
    <img src="{{ $trackingUrl }}" alt="" class="pixel-tracker">
    @endif
</body>
</html>
