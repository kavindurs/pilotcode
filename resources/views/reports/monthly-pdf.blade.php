<!-- filepath: c:\xampp\htdocs\pilot\resources\views\reports\monthly-pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Review Report - {{ $month }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .property-info {
            margin-bottom: 20px;
        }
        h1 {
            color: #2563eb;
            margin-bottom: 5px;
        }
        h2 {
            color: #374151;
            margin-top: 30px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .rating-overview {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .rating-card {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 15px;
            width: 30%;
            text-align: center;
        }
        .rating-value {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }
        .rating-label {
            font-size: 14px;
            color: #6b7280;
        }
        .star-rating {
            color: #f59e0b;
            font-size: 20px;
        }
        .distribution {
            margin-bottom: 30px;
        }
        .distribution-row {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        .star-label {
            width: 80px;
        }
        .progress-bar {
            flex-grow: 1;
            background-color: #e5e7eb;
            height: 12px;
            border-radius: 6px;
            overflow: hidden;
        }
        .progress-fill {
            background-color: #2563eb;
            height: 100%;
        }
        .count-label {
            width: 50px;
            text-align: right;
            padding-left: 10px;
        }
        .review-item {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .user-info {
            font-weight: bold;
        }
        .review-date {
            color: #6b7280;
            font-size: 14px;
        }
        .review-rating {
            color: #f59e0b;
        }
        .review-text {
            color: #4b5563;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Monthly Review Report</h1>
            <p>{{ $month }}</p>
        </div>

        <div class="property-info">
            <h2>{{ $property->business_name }}</h2>
            <p>Report Generated: {{ $generatedDate }}</p>
        </div>

        <h2>Rating Overview</h2>
        <div class="rating-overview">
            <div class="rating-card">
                <div class="rating-value">{{ number_format($averageRating, 1) }}</div>
                <div class="star-rating">★★★★★</div>
                <div class="rating-label">Average Rating</div>
            </div>

            <div class="rating-card">
                <div class="rating-value">{{ $totalReviews }}</div>
                <div class="rating-label">Total Reviews</div>
            </div>

            <div class="rating-card">
                <div class="rating-value">{{ round(($statistics['sentimentData']['positive'] + $statistics['sentimentData']['neutral']), 1) }}%</div>
                <div class="rating-label">Positive + Neutral</div>
            </div>
        </div>

        <h2>Rating Distribution</h2>
        <div class="distribution">
            @foreach(range(5, 1) as $rating)
                <div class="distribution-row">
                    <div class="star-label">{{ $rating }} Stars</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $totalReviews > 0 ? ($statistics['ratingDistribution'][$rating] / $statistics['totalReviews'] * 100) : 0 }}%"></div>
                    </div>
                    <div class="count-label">{{ $statistics['ratingDistribution'][$rating] }}</div>
                </div>
            @endforeach
        </div>

        <h2>Recent Reviews</h2>
        @if($reviews->count() > 0)
            @foreach($reviews->take(10) as $review)
                <div class="review-item">
                    <div class="review-header">
                        <div class="user-info">{{ $review->user ? $review->user->name : 'Anonymous' }}</div>
                        <div class="review-date">{{ $review->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="review-rating">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rate)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                        ({{ $review->rate }})
                    </div>
                    <div class="review-text">
                        {{ $review->review ?? 'No comment provided.' }}
                    </div>
                </div>
            @endforeach
        @else
            <p>No reviews received this month.</p>
        @endif

        <div class="footer">
            <p>This report is generated automatically and contains confidential information.</p>
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
