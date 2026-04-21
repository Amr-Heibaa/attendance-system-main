<?php

namespace App\Exports;

use App\Services\ReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DepartmentReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    public function __construct(
        private string $month,
        private ReportService $reportService
    ) {}

    public function collection()
    {
        return $this->reportService->getDepartmentMonthlySummary($this->month)
            ->map(fn($row) => [
                'القسم'              => $row['department'],
                'عدد الموظفين'       => $row['employees_count'],
                'أيام حضور'          => $row['present_days'],
                'أيام تأخير'         => $row['late_days'],
                'أيام غياب'          => $row['absent_days'],
                'أيام إجازة'         => $row['leave_days'],
                'إجمالي ساعات العمل' => $row['total_work_hours'],
            ]);
    }

    public function headings(): array
    {
        return [
            'القسم',
            'عدد الموظفين',
            'أيام حضور',
            'أيام تأخير',
            'أيام غياب',
            'أيام إجازة',
            'إجمالي ساعات العمل',
        ];
    }

    public function title(): string
    {
        return 'تقرير الأقسام ' . $this->month;
    }
}