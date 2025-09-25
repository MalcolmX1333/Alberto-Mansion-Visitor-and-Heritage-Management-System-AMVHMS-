<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use MattDaneshvar\Survey\Models\Entry;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReservationDetailsExport implements FromArray, WithStyles
{
    protected $period;
    protected $dataRows = [];

    public function __construct($period = null)
    {
        $this->period = $period;
    }

    public function array(): array
    {
        // Only return data rows (no headers)
        $reservations = $this->getReservationsWithTimeTracking();

        $this->dataRows = collect($reservations)->map(function ($reservation) {
            return [
                // Removed $reservation['survey_name'],
                $reservation['full_name'],
                $reservation['visit_date'],
                $reservation['registration_type'],
                $reservation['cn_bus_number'] ?? '-',
                $reservation['address'] ?? '-',
                $reservation['nationality'] ?? '-',
                $reservation['gender'] ?? '-',
                $reservation['participant_name'],
                $reservation['participant_email'],
                $reservation['status'],
                $reservation['created_at'],
                $reservation['time_in'] ?? '-',
                $reservation['time_out'] ?? '-',
                $reservation['demographics']['grade_school'] ?? '-',
                $reservation['demographics']['high_school'] ?? '-',
                $reservation['demographics']['college_gradschool'] ?? '-',
                $reservation['demographics']['pwd'] ?? '-',
                $reservation['demographics']['age_17_below'] ?? '-',
                $reservation['demographics']['age_18_30'] ?? '-',
                $reservation['demographics']['age_31_45'] ?? '-',
                $reservation['demographics']['age_60_above'] ?? '-',
            ];
        })->toArray();

        return $this->dataRows;
    }

    private function getReservationsWithTimeTracking()
    {
        // Base query similar to AdminReservationController
        $query = "
            SELECT
                e1.id,
                s.name as survey_name,
                e1.participant_id,
                e1.created_at,
                e1.updated_at,
                e1.isVisited,
                CASE
                    WHEN e1.isVisited = 1 THEN e1.updated_at
                    ELSE NULL
                END as time_in,
                e2.created_at as time_out,
                e2.id as feedback_entry_id
            FROM entries e1
            LEFT JOIN surveys s ON e1.survey_id = s.id
            LEFT JOIN entries e2 ON e1.participant_id = e2.participant_id
                AND e2.survey_id = 2
            WHERE e1.survey_id = 1
        ";

        // Add date filtering based on period
        if ($this->period) {
            switch ($this->period) {
                case 'monthly':
                    $query .= " AND e1.created_at BETWEEN '" . now()->startOfMonth()->toDateTimeString() . "' AND '" . now()->endOfMonth()->toDateTimeString() . "'";
                    break;
                case 'quarterly':
                    $query .= " AND e1.created_at BETWEEN '" . now()->firstOfQuarter()->toDateTimeString() . "' AND '" . now()->lastOfQuarter()->toDateTimeString() . "'";
                    break;
                case 'semi-annually':
                    $query .= " AND e1.created_at BETWEEN '" . now()->startOfYear()->toDateTimeString() . "' AND '" . now()->startOfYear()->addMonths(6)->endOfMonth()->toDateTimeString() . "'";
                    break;
                case 'annually':
                    $query .= " AND e1.created_at BETWEEN '" . now()->startOfYear()->toDateTimeString() . "' AND '" . now()->endOfYear()->toDateTimeString() . "'";
                    break;
            }
        }

        $query .= " ORDER BY e1.created_at DESC";

        $reservations = DB::select($query);

        return collect($reservations)->map(function ($reservation) {
            $entry = Entry::with(['survey', 'answers.question', 'participant'])
                ->find($reservation->id);

            $result = [
                'id' => $reservation->id,
                'survey_name' => $reservation->survey_name,
                'visit_date' => $this->getAnswerValue($entry, 'Visit Date and Time'),
                'registration_type' => $this->getAnswerValue($entry, 'Registration Type'),
                'full_name' => $this->getAnswerValue($entry, 'Full name'),
                'cn_bus_number' => $this->getAnswerValue($entry, 'C.N. Bus Number'),
                'address' => $this->getAnswerValue($entry, 'Address/Affliation'),
                'nationality' => $this->getAnswerValue($entry, 'Nationality'),
                'gender' => $this->getAnswerValue($entry, 'Gender'),
                'participant_name' => $entry->participant ? $entry->participant->name : 'Guest',
                'participant_email' => $entry->participant ? $entry->participant->email : '-',
                'created_at' => $reservation->created_at,
                'status' => $reservation->isVisited ? 'Visited' : 'Pending',
                'time_in' => $reservation->time_in ? date('Y-m-d H:i:s', strtotime($reservation->time_in)) : null,
                'time_out' => $reservation->time_out ? date('Y-m-d H:i:s', strtotime($reservation->time_out)) : null,
                'feedback_entry_id' => $reservation->feedback_entry_id,
            ];

            // Add demographic information for group registrations
            if ($this->getAnswerValue($entry, 'Registration Type') === 'Group') {
                $result['demographics'] = [
                    'grade_school' => $this->getAnswerValue($entry, 'No. of Students / Grade School'),
                    'high_school' => $this->getAnswerValue($entry, 'No. of Students / High School'),
                    'college_gradschool' => $this->getAnswerValue($entry, 'No. of Students / College / GradSchool'),
                    'pwd' => $this->getAnswerValue($entry, 'PWD'),
                    'age_17_below' => $this->getAnswerValue($entry, '17 y/o and below'),
                    'age_18_30' => $this->getAnswerValue($entry, '18-30 y/o'),
                    'age_31_45' => $this->getAnswerValue($entry, '31-45 y/o'),
                    'age_60_above' => $this->getAnswerValue($entry, '60 y/o and above')
                ];
            } else {
                $result['demographics'] = [
                    'grade_school' => '-',
                    'high_school' => '-',
                    'college_gradschool' => '-',
                    'pwd' => '-',
                    'age_17_below' => '-',
                    'age_18_30' => '-',
                    'age_31_45' => '-',
                    'age_60_above' => '-'
                ];
            }

            return $result;
        })->toArray();
    }

    private function getAnswerValue($entry, $questionContent)
    {
        if (!$entry || !$entry->answers) {
            return '-';
        }

        $answer = $entry->answers->first(function ($answer) use ($questionContent) {
            return $answer->question && $answer->question->content === $questionContent;
        });

        return $answer ? $answer->value : '-';
    }

    // Move headings() here so it's defined before styles()
    public function headings(): array
    {
        return [
            // Removed 'Survey Name',
            'Full Name',
            'Visit Date',
            'Registration Type',
            'C.N. Bus Number',
            'Address/Affiliation',
            'Nationality',
            'Gender',
            'Participant Name',
            'Participant Email',
            'Status',
            'Created At',
            'Time In',
            'Time Out',
            'Grade School Students',
            'High School Students',
            'College/GradSchool Students',
            'PWD',
            'Age 17 Below',
            'Age 18-30',
            'Age 31-45',
            'Age 60 Above'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Update all merged cell ranges and styling to use column U (21 columns) instead of V (22 columns)

        // Company info
        $sheet->mergeCells('A1:U1');
        $sheet->setCellValue('A1', 'National Historical Commission of the Philippines');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['argb' => 'FF000000'], // Black text
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->mergeCells('A2:U2');
        $sheet->setCellValue('A2', 'Historical Sites and Education Division');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['argb' => 'FF000000'], // Black text
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->mergeCells('A3:U3');
        $sheet->setCellValue('A3', 'Alberto Mansion');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FF000000'], // Black text
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->mergeCells('A4:U4');
        $sheet->setCellValue('A4', 'Biñan, Laguna');
        $sheet->getStyle('A4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FF000000'], // Black text
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->mergeCells('A5:U5');
        $sheet->setCellValue('A5', '');

        $sheet->mergeCells('A6:U6');
        $sheet->setCellValue('A6', '');

        $sheet->mergeCells('A7:U7');
        $sheet->setCellValue('A7', 'RESERVATION DETAILS REPORT');
        $sheet->getStyle('A7')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['argb' => 'FF000000'], // Black text
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        if ($this->period) {
            $sheet->mergeCells('A8:U8');
            $sheet->setCellValue('A8', 'Period: ' . ucfirst($this->period));
            $sheet->getStyle('A8')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FF000000'], // Black text
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);
        } else {
            $sheet->mergeCells('A8:U8');
            $sheet->setCellValue('A8', '');
        }

        // Grouped headers at A9
        // Personal Information group: A9:G9 (columns 1-7)
        $sheet->mergeCells('A9:G9');
        $sheet->setCellValue('A9', 'Personal Information');
        $sheet->getStyle('A9')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFFFF'], // White text
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF2196F3', // Blue background color for groups
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Black border
                ],
            ],
        ]);

        // Visit Information group: H9:M9 (columns 8-13)
        $sheet->mergeCells('H9:M9');
        $sheet->setCellValue('H9', 'Visit Information');
        $sheet->getStyle('H9')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFFFF'], // White text
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFFF9800', // Orange background color for groups
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Black border
                ],
            ],
        ]);

        // Demographics group: N9:U9 (columns 14-21)
        $sheet->mergeCells('N9:U9');
        $sheet->setCellValue('N9', 'Demographics');
        $sheet->getStyle('N9')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFFFF'], // White text
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF4CAF50', // Green background color for groups
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Black border
                ],
            ],
        ]);

        // Set the headers from array starting at A10
        $sheet->fromArray($this->headings(), null, 'A10');

        // Set the data rows starting at A11
        $sheet->fromArray($this->dataRows, null, 'A11');

        // Get the highest column and row after data is written
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        $rowCount = $sheet->getHighestRow();

        // Style the header row (A10:U10)
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $cell = $sheet->getCellByColumnAndRow($col, 10);
            if ($cell->getValue() !== '') {
                $sheet->getStyleByColumnAndRow($col, 10)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'], // White text
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FF607D8B', // Blue-gray background color for headers
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'], // Black border
                        ],
                    ],
                ]);
            }
        }

        // Apply alternating row colors for data rows (start at row 11)
        for ($row = 11; $row <= $rowCount; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle('A'.$row.':'.$highestColumn.$row)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FFE0E0E0', // Light gray background color for even rows
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FFCCCCCC'], // Light gray border
                        ],
                    ],
                ]);
            } else {
                $sheet->getStyle('A'.$row.':'.$highestColumn.$row)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FFFFFFFF', // White background color for odd rows
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FFCCCCCC'], // Light gray border
                        ],
                    ],
                ]);
            }
        }

        // Auto-size columns for better readability
        foreach (range('A', $highestColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Format datetime columns (K, L, M) for data rows (shifted left by one)
        $sheet->getStyle('K11:M' . $rowCount)->getNumberFormat()->setFormatCode('yyyy-mm-dd hh:mm:ss');

        return [
            10 => ['font' => ['bold' => true]], // Bold headings on row 10
        ];
    }
}
