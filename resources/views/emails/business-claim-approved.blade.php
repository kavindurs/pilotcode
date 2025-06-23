<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Claim Approved</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .success-icon {
            text-align: center;
            margin: 20px 0;
        }
        .success-icon i {
            font-size: 60px;
            color: #4CAF50;
        }
        .credentials-box {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
            border-left: 4px solid #667eea;
        }
        .credentials-box h3 {
            margin-top: 0;
            color: #333;
            font-size: 18px;
        }
        .credential-item {
            margin: 15px 0;
            display: flex;
            align-items: center;
        }
        .credential-label {
            font-weight: bold;
            color: #555;
            min-width: 80px;
        }
        .credential-value {
            background-color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            border: 1px solid #ddd;
            margin-left: 10px;
            flex: 1;
        }
        .login-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .login-button:hover {
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
            background-color: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 Business Claim Approved!</h1>
        </div>

        <div class="content">
            <div class="success-icon">
                ✅
            </div>

            <h2>Congratulations, {{ $businessName }}!</h2>

            <p>Great news! Your business claim has been approved and processed successfully. You now have full access to manage your business property on our platform.</p>

            <div class="credentials-box">
                <h3>🔐 Your Login Credentials</h3>
                <p style="margin-bottom: 15px;">Please save these credentials securely. You'll need them to access your business dashboard:</p>

                <div class="credential-item">
                    <span class="credential-label">Email:</span>
                    <span class="credential-value">{{ $email }}</span>
                </div>

                <div class="credential-item">
                    <span class="credential-label">Password:</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
            </div>

            <div class="info-box">
                <strong>Property ID:</strong> {{ $propertyId }}<br>
                <strong>Status:</strong> Approved ✅
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $loginUrl }}" class="login-button">
                    🚀 Login to Your Dashboard
                </a>
            </div>

            <h3>What's Next?</h3>
            <ul>
                <li>📊 Access your business dashboard</li>
                <li>📝 Update your business information</li>
                <li>⭐ Manage customer reviews</li>
                <li>📈 Track your business performance</li>
                <li>🎯 Optimize your business profile</li>
            </ul>

            <div class="info-box">
                <strong>💡 Security Tip:</strong> We recommend changing your password after your first login for enhanced security.
            </div>

            <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>

            <p>Welcome aboard! 🎊</p>
        </div>

        <div class="footer">
            <p>This email was sent automatically when your business claim was approved.</p>
            <p>© {{ date('Y') }} Scoreness. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
