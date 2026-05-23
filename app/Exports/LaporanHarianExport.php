<?php

namespace App\Exports;

use App\Models\LaporanHarian;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanHarianExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize,
    WithEvents
{
    protected array $filters;
    protected int   $rowCount = 0;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = LaporanHarian::with([
            'user:id,name',
            'pompa:id,kodepompa,jenispompa,lokasi_id',
            'pompa.lokasi:id,kodesp,namasp',
            'detilJam',
        ])->where('is_deleted', 0);

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('injeksi_ke', 'like', "%{$search}%")
                  ->orWhereHas('pompa', fn($q2) =>
                        $q2->where('kodepompa', 'like', "%{$search}%"))
                  ->orWhereHas('user', fn($q3) =>
                        $q3->where('name', 'like', "%{$search}%"));
            });
        }

        if (!empty($this->filters['date_from']) && !empty($this->filters['date_to'])) {
            $query->whereBetween('tanggal', [
                $this->filters['date_from'],
                $this->filters['date_to'],
            ]);
        } elseif (!empty($this->filters['date_from'])) {
            $query->whereDate('tanggal', '>=', $this->filters['date_from']);
        } elseif (!empty($this->filters['date_to'])) {
            $query->whereDate('tanggal', '<=', $this->filters['date_to']);
        }

        if (!empty($this->filters['lokasi_id'])) {
            $query->where('lokasisp_id', $this->filters['lokasi_id']);
        }

        $validStatuses = ['draft', 'finalized', 'verified', 'approved'];
        if (!empty($this->filters['status']) && in_array($this->filters['status'], $validStatuses)) {
            $query->where('status', $this->filters['status']);
        }

        $collection = $query->orderBy('tanggal', 'desc')->get();
        $this->rowCount = $collection->count();

        return $collection;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Kode Pompa',
            'Jenis Pompa',
            'Lokasi SP',
            'Kode SP',
            'Injector Well',
            'User Input',
            'Entry Jam',
            'Total Cumulative (BBLS)',
            'Status',
            'Dibuat Pada',
            'Diupdate Pada',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $statusLabel = [
            'draft'     => 'Draft',
            'finalized' => 'Finalized',
            'verified'  => 'Verified',
            'approved'  => 'Approved',
            'rejected'  => 'Rejected',
        ];

        return [
            $no,
            Carbon::parse($row->tanggal)->format('d/m/Y'),
            $row->pompa->kodepompa      ?? '-',
            $row->pompa->jenispompa     ?? '-',
            $row->pompa->lokasi->namasp ?? '-',
            $row->pompa->lokasi->kodesp ?? '-',
            $row->injeksi_ke            ?? '-',
            $row->user->name            ?? '-',
            $row->detilJam->count() . ' entry',
            number_format($row->total_cumulative ?? 0, 2),
            $statusLabel[$row->status]  ?? $row->status,
            Carbon::parse($row->created_at)->format('d/m/Y H:i'),
            Carbon::parse($row->updated_at)->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row
            3 => [
                'font' => [
                    'bold'  => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size'  => 11,
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Harian';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet     = $event->sheet->getDelegate();
                $lastRow   = $this->rowCount + 3; // +3 karena header mulai baris 3
                $lastCol   = 'M';

                // ── Baris 1: Judul laporan ─────────────────────────
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->setCellValue('A1', 'LAPORAN HARIAN INJEKSI POMPA - PT PERTAMINA EP ASSET 2 LIMAU FIELD');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F3864']],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // ── Baris 2: Info export ───────────────────────────
                $sheet->mergeCells("A2:{$lastCol}2");
                $filterInfo = 'Diekspor pada: ' . now()->format('d/m/Y H:i');
                if (!empty($this->filters['date_from']) || !empty($this->filters['date_to'])) {
                    $filterInfo .= ' | Periode: '
                        . ($this->filters['date_from'] ?? '-')
                        . ' s/d '
                        . ($this->filters['date_to']   ?? '-');
                }
                if (!empty($this->filters['status'])) {
                    $filterInfo .= ' | Status: ' . ucfirst($this->filters['status']);
                }
                $sheet->setCellValue('A2', $filterInfo);
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '666666']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(18);

                // ── Border seluruh tabel ───────────────────────────
                if ($this->rowCount > 0) {
                    $sheet->getStyle("A3:{$lastCol}{$lastRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color'       => ['rgb' => 'CCCCCC'],
                            ],
                            'outline' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color'       => ['rgb' => '4472C4'],
                            ],
                        ],
                    ]);

                    // ── Warna baris data (zebra) ───────────────────
                    for ($i = 4; $i <= $lastRow; $i++) {
                        $color = ($i % 2 === 0) ? 'F8F9FF' : 'FFFFFF';
                        $sheet->getStyle("A{$i}:{$lastCol}{$i}")->applyFromArray([
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $color],
                            ],
                            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                        ]);
                    }

                    // ── Kolom No & Entry Jam center ────────────────
                    $sheet->getStyle("A4:A{$lastRow}")->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("I4:I{$lastRow}")->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("J4:J{$lastRow}")->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("K4:K{$lastRow}")->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // ── Baris total di akhir ───────────────────────
                    $totalRow = $lastRow + 1;
                    $sheet->mergeCells("A{$totalRow}:I{$totalRow}");
                    $sheet->setCellValue("A{$totalRow}", 'TOTAL LAPORAN: ' . $this->rowCount);
                    $sheet->setCellValue("J{$totalRow}", "=SUM(J4:J{$lastRow})");
                    $sheet->getStyle("A{$totalRow}:{$lastCol}{$totalRow}")->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType'   => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E8F0FE'],
                        ],
                        'borders' => [
                            'outline' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color'       => ['rgb' => '4472C4'],
                            ],
                        ],
                    ]);
                }

                // ── Freeze pane di baris header ────────────────────
                $sheet->freezePane('A4');

                // ── Row height data ────────────────────────────────
                for ($i = 4; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(20);
                }
            },
        ];
    }
}