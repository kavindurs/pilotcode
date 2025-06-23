<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .status-approved {
            color: #27ae60;
            font-weight: bold;
        }
        .status-rejected {
            color: #e74c3c;
            font-weight: bold;
        }
        .login-details {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
        }
        .login-details h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .credentials {
            font-family: 'Courier New', monospace;
            background-color: #fff;
            padding: 10px;
            border-radius: 3px;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            color: #7f8c8d;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Property Status Update</h1>
        </div>        <div class="content">
            <p>Dear {{ $property->first_name }} {{ $property->last_name }},</p>

            @if($action === 'Approved')
                <p>We are pleased to inform you that your property <strong>"{{ $property->business_name }}"</strong> has been <span class="status-approved">approved</span>.</p>
                <p>You can now access your property dashboard and start managing your business profile.</p>

            @elseif($action === 'Rejected')
                <p>We regret to inform you that your property <strong>"{{ $property->business_name }}"</strong> has been <span class="status-rejected">rejected</span>.</p>
                <p>Please review your submission and feel free to resubmit with the necessary corrections.</p>

            @elseif($action === 'Approved for Claim')
                <p>We are pleased to inform you that your property <strong>"{{ $property->business_name }}"</strong> has been <span class="status-approved">approved for claim</span>.</p>
                <p>Your property is now available for claiming. An administrator will review and process your claim request.</p>

            @elseif($action === 'Claim Request Rejected')
                <p>We regret to inform you that your claim request for property <strong>"{{ $property->business_name }}"</strong> has been <span class="status-rejected">rejected</span>.</p>
                <p>Please contact our support team if you believe this decision was made in error.</p>

            @elseif($action === 'Successfully Claimed')
                <p>Congratulations! Your property <strong>"{{ $property->business_name }}"</strong> has been <span class="status-approved">successfully claimed</span>.</p>
                <p>Your account has been set up and you now have full access to manage your business profile.</p>

                @if($newLoginEmail && $newPassword)
                <div class="login-details">
                    <h3>🔐 Your New Login Credentials</h3>
                    <p>You can now log in to your account using the following credentials:</p>
                    <div class="credentials">
                        <strong>Email:</strong> {{ $newLoginEmail }}<br>
                        <strong>Password:</strong> {{ $newPassword }}
                    </div>
                    <p><em>For security reasons, we recommend changing your password after your first login.</em></p>
                </div>
                @endif
            @else
                <p>We are writing to inform you about an update regarding your property <strong>"{{ $property->business_name }}"</strong>.</p>
                <p><strong>Status:</strong> {{ $action }}</p>
            @endif

            <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p><em>This is an automated email. Please do not reply to this message.</em></p>
        </div>
    </div>
</body>
</html>
