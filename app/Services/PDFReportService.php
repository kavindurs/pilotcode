<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PDFReportService
{
    public function generateMonthlyReport($property, $reviews, $statistics)
    {
        $currentMonth = now()->format('F Y');

        // Filter reviews for the current month
        $monthlyReviews = $reviews->filter(function($review) {
            return $review->created_at->format('F Y') === now()->format('F Y');
        });

        $monthlyAverage = $monthlyReviews->avg('rate') ?? 0;

        // Build PDF data
        $data = [
            'property' => $property,
            'reviews' => $monthlyReviews,
            'allReviews' => $reviews,
            'totalReviews' => $monthlyReviews->count(),
            'averageRating' => $monthlyAverage,
            'statistics' => $statistics,
            'month' => $currentMonth,
            'generatedDate' => now()->format('Y-m-d'),
        ];

        // Generate PDF
        $pdf = PDF::loadView('reports.monthly-pdf', $data);
        return $pdf;
    }
}
