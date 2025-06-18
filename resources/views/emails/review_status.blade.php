<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Review Status</title>
</head>
<body>
    <p>Dear {{ $review->user->name ?? 'User' }},</p>
    @if($action === 'approved')
        <p>Your review has been approved.</p>
    @else
        <p>Your review has been rejected.</p>
    @endif
    <p>Thank you.</p>
</body>
</html>
