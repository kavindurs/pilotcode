<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReviewsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $reviews;
    protected $property;
    protected $statistics;

    public function __construct($reviews, $property, $statistics)
    {
        $this->reviews = $reviews;
        $this->property = $property;
        $this->statistics = $statistics;
    }

    public function collection()
    {
        return $this->reviews;
    }

    public function headings(): array
    {
        return [
            'Review ID',
            'User Name',
            'Rating',
            'Comment',
            'Date Posted',
            'Experience Date',
        ];
    }

    public function map($review): array
    {
        return [
            $review->id,
            $review->user ? $review->user->name : 'Anonymous',
            $review->rate,
            $review->review,
            $review->created_at->format('Y-m-d'),
            $review->experienced_date ? date('Y-m-d', strtotime($review->experienced_date)) : 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Add property name and statistics to the first rows
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'Review Data for: ' . $this->property->business_name);

        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', 'Average Rating: ' . number_format($this->statistics['averageRating'], 1) .
                                   ' | Total Reviews: ' . $this->statistics['totalReviews']);

        $sheet->mergeCells('A3:F3');
        $sheet->setCellValue('A3', 'Generated on: ' . now()->format('Y-m-d H:i:s'));

        // Empty row
        $sheet->mergeCells('A4:F4');

        // Style headers
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getFont()->setSize(14);
        $sheet->getStyle('A2:F3')->getFont()->setSize(12);
        $sheet->getStyle('A5:F5')->getFont()->setBold(true);
        $sheet->getStyle('A5:F5')->getFont()->setSize(12);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(50);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);

        return [
            5 => ['font' => ['bold' => true]],
        ];
    }
}
