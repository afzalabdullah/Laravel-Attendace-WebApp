<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $attendances;

    public function __construct($attendances)
    {
        $this->attendances = $attendances;
    }

    public function collection()
    {
        return $this->attendances;
    }



    public function headings(): array
    {
        return [
            'Employee Code',
            'Employee Name',
            'Department',
            'Date',
            'Check-in Time',
            'Check-out Time',
            'Duty Hours',
        ];
    }
}
