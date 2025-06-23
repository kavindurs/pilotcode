<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Claim Update Required</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #ff7b7b 0%, #ff6b9d 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .content {
            padding: 30px;
        }
        .info-icon {
            text-align: center;
            margin: 20px 0;
        }
        .info-icon i {
            font-size: 60px;
            color: #ff9800;
        }
        .feedback-box {
            background: linear-gradient(135deg, #fff3e0 0%, #ffecb3 100%);
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
            border-left: 4px solid #ff9800;
        }
        .feedback-box h3 {
            margin-top: 0;
            color: #333;
            font-size: 18px;
        }
        .admin-notes {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            font-style: italic;
            color: #555;
            margin: 15px 0;
        }
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 5px;
            text-align: center;
        }
        .secondary-button {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        }
        .action-button:hover {
            opacity: 0.9;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .info-box {
            background-color: #e8f5e8;
            border-left: 4px solid #4CAF50;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📋 Business Claim Update</h1>
        </div>

        <div class="content">
            <div class="info-icon">
                ⚠️
            </div>

            <h2>Hello {{ $businessName }},</h2>

            <p>Thank you for submitting your business claim request. After careful review, we need some additional information or corrections before we can approve your claim.</p>

            <div class="feedback-box">
                <h3>📝 Feedback from Our Review Team</h3>
                <p style="margin-bottom: 15px;">Our team has provided the following feedback to help you complete your claim:</p>

                <div class="admin-notes">
                    "{{ $adminNotes }}"
                </div>
            </div>

            <div class="warning-box">
                <strong>📊 Claim Details:</strong><br>
                <strong>Business:</strong> {{ $businessName }}<br>
                <strong>Email:</strong> {{ $email }}<br>
                @if($propertyId)
                <strong>Property ID:</strong> {{ $propertyId }}<br>
                @endif
                <strong>Status:</strong> Requires Updates 📋
            </div>

            <h3>What's Next?</h3>
            <p>Don't worry! This is a common part of the verification process. Here's what you can do:</p>

            <ul>
                <li>📝 Review the feedback provided above</li>
                <li>🔄 Make the necessary corrections or gather additional information</li>
                <li>📤 Submit a new claim with the updated information</li>
                <li>💬 Contact our support team if you need clarification</li>
            </ul>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $resubmitUrl }}" class="action-button">
                    🔄 Submit New Claim
                </a>
                <a href="{{ $supportUrl }}" class="action-button secondary-button">
                    💬 Contact Support
                </a>
            </div>

            <div class="info-box">
                <strong>💡 Helpful Tips:</strong>
                <ul style="margin: 10px 0;">
                    <li>Ensure all business information is accurate and up-to-date</li>
                    <li>Provide clear, high-quality documentation if required</li>
                    <li>Double-check that your business email matches your domain</li>
                    <li>Include all requested verification materials</li>
                </ul>
            </div>

            <p>We appreciate your patience and look forward to helping you claim your business successfully. Our goal is to ensure all information is accurate and verified for the best experience.</p>

            <p>If you have any questions about the feedback or need assistance with your resubmission, please don't hesitate to reach out to our support team.</p>

            <p>Thank you for choosing our platform! 🙏</p>
        </div>

        <div class="footer">
            <p>This email was sent regarding your business claim submission.</p>
            <p>© {{ date('Y') }} Scoreness. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
