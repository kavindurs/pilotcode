<!--<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Property Status</title>
</head>
<body>
    <p>Dear {{ $property->first_name }} {{ $property->last_name }},</p>
    @if($action === 'approved')
        <p>We are pleased to inform you that your property ({{ $property->business_name }}) has been approved.</p>
    @else
        <p>We regret to inform you that your property ({{ $property->business_name }}) has been rejected.</p>
    @endif
    <p>Thank you.</p>
</body>
</html>
-->
