<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DepartmentReportExport implements FromCollection, WithHeadings
{
    protected $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    public function collection()
    {
        return collect($this->reportData);
    }

    public function headings(): array
    {
        return [
            'Employee Code',
            'Name',
            'Department',
            'Date',
            'Check-In Time',
            'Check-Out Time',
            'Duty Hours',
            'Status',
            'Leave Type',
        ];
    }
}
