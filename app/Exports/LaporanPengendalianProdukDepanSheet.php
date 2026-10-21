<?php

namespace App\Exports;

use App\Models\ProductionBatch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class LaporanPengendalianProdukDepanSheet implements
    FromCollection,
    WithColumnWidths,
    WithEvents,
    WithTitle
{
    protected ProductionBatch $productionBatch;

    public function __construct(ProductionBatch $productionBatch)
    {
        $this->productionBatch = $productionBatch;
    }

    public function collection()
    {
        return collect([]);
    }

    public function title(): string
    {
        return 'Lembar Depan';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 19,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 15,
            'N' => 15,
            'O' => 17,
            'P' => 19,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $this->setupPage($sheet);
                $this->buildHeader($sheet);

                $row = 6;

                $row = $this->buildProduction($sheet, $row);
                $row = $this->buildBahanBaku($sheet, $row);
                $row = $this->buildDataHasilProduksi($sheet, $row);

                $row = $this->buildProcessTable(
                    $sheet,
                    $row,
                    'REKAPITULASI PRODUKSI & MONITORING MESIN',
                    [
                        'Mesin',
                        'Waktu Start',
                        'Waktu Stop',
                        'Operation Time',
                        'Speed Kapasitas',
                        'Aktual',
                        '% Pencapaian',
                        'Output Kg',
                        'Downtime',
                        'Penyebab Downtime',
                        'Tindakan Perbaikan',
                        'PIC/Operator',
                    ],
                    $this->productionBatch->rekapitulasiProduksis
                        ->map(fn ($item) => [
                            $item->mesin ?? '-',
                            $this->time($item->waktu_start),
                            $this->time($item->waktu_stop),
                            $this->number($item->operation_time),
                            $this->number($item->speed_kapasitas),
                            $this->number($item->aktual),
                            $this->number($item->persen_pencapaian) . ' %',
                            $this->number($item->output_kg),
                            $this->number($item->downtime),
                            $item->penyebab_downtime ?? '-',
                            $item->tindakan_perbaikan ?? '-',
                            $item->pic_operator ?? '-',
                        ])
                        ->values()
                        ->all()
                );

                $row = $this->buildProcessTable(
                    $sheet,
                    $row,
                    'PRODUCTIVITY',
                    [
                        'Material',
                        'Worker Indirect',
                        'Worker Direct',
                        'Worker Total',
                        'Total Weight',
                        'Total Time',
                        'Productivity Indirect',
                        'Productivity Direct',
                    ],
                    $this->productionBatch->productivities
                        ->map(fn ($item) => [
                            $item->material->material_description ?? '-',
                            $item->worker_indirect ?? '-',
                            $item->worker_direct ?? '-',
                            $item->worker_total ?? '-',
                            $this->number($item->total_weight),
                            $this->number($item->total_time),
                            $this->number($item->productivity_indirect),
                            $this->number($item->productivity_direct),
                        ])
                        ->values()
                        ->all()
                );

                $this->buildApproval($sheet, $row);

                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle("A1:P{$lastRow}")
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                $sheet->getDefaultRowDimension()->setRowHeight(28);
                $sheet->setShowGridlines(false);
            },
        ];
    }

    protected function setupPage($sheet): void
    {
        $pageSetup = $sheet->getPageSetup();

        $pageSetup
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToWidth(1)
            ->setFitToHeight(0);

        $sheet->getPageMargins()
            ->setTop(0.25)
            ->setRight(0.2)
            ->setBottom(0.25)
            ->setLeft(0.2);

        $sheet->getSheetView()->setZoomScale(75);
    }

    protected function buildHeader($sheet): void
    {
        $sheet->mergeCells('A1:P1');
        $sheet->setCellValue('A1', 'PT CHAROEN POKPHAND INDONESIA');

        $sheet->mergeCells('A2:P2');
        $sheet->setCellValue('A2', 'FOOD DIVISION');

        $sheet->mergeCells('A3:P3');
        $sheet->setCellValue('A3', 'LAPORAN PENGENDALIAN PRODUK');

        $tanggal = $this->productionBatch->tanggal
            ? $this->productionBatch->tanggal->format('d/m/Y')
            : '-';

        $sheet->mergeCells('A4:P4');
        $sheet->setCellValue(
            'A4',
            'PRODUCTION BATCH : ' . $tanggal .
            ' - SHIFT ' . ($this->productionBatch->shift ?? '-')
        );

        $sheet->getStyle('A1:P4')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(14);

        $sheet->getRowDimension(1)->setRowHeight(36);
        $sheet->getRowDimension(2)->setRowHeight(30);
        $sheet->getRowDimension(3)->setRowHeight(38);
        $sheet->getRowDimension(4)->setRowHeight(34);
    }

    protected function buildProduction($sheet, int $row): int
    {
        $pb = $this->productionBatch;

        $pairs = [
            [
                'Tanggal',
                $pb->tanggal ? $pb->tanggal->format('d/m/Y') : '-',
                'Absen Worker',
                $pb->absen_worker ?? '-',
            ],
            [
                'Shift',
                $pb->shift ?? '-',
                'Tanggal Transfer',
                $pb->tanggal_transfer ? $pb->tanggal_transfer->format('d/m/Y') : '-',
            ],
            [
                'Conveyor',
                $pb->conveyor ?? '-',
                'Tanggal Proses',
                $pb->tanggal_proses ? $pb->tanggal_proses->format('d/m/Y') : '-',
            ],
        ];

        foreach ($pairs as $i => [$label1, $value1, $label2, $value2]) {
            $r = $row + $i;

            $cells = [
                ['A', 'C', $label1 . ' :'],
                ['D', 'G', $value1],
                ['H', 'J', $label2 . ' :'],
                ['K', 'P', $value2],
            ];

            foreach ($cells as [$start, $end, $value]) {
                $sheet->mergeCells("{$start}{$r}:{$end}{$r}");
                $sheet->setCellValue("{$start}{$r}", $value);

                $sheet->getStyle("{$start}{$r}:{$end}{$r}")
                    ->getFont()
                    ->setBold(true)
                    ->setSize(12);

                $sheet->getStyle("{$start}{$r}:{$end}{$r}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);
            }

            $sheet->getRowDimension($r)->setRowHeight(30);
        }

        return $row + count($pairs) + 1;
    }

    protected function buildBahanBaku($sheet, int $row): int
    {
        $this->sectionTitle($sheet, $row, 'DATA BAHAN BAKU', 'A', 'P');

        $row++;

        $columns = [
            'No. PO',
            'Cost Center Name',
            'Material',
            'Jumlah Bag',
            'Jumlah Kg',
        ];

        $spans = $this->distributeColumns(count($columns));

        foreach ($columns as $i => $label) {
            [$start, $end] = $spans[$i];

            if ($start !== $end) {
                $sheet->mergeCells("{$start}{$row}:{$end}{$row}");
            }

            $sheet->setCellValue("{$start}{$row}", $label);
        }

        $this->styleRow($sheet, $row, 14, true);
        $sheet->getRowDimension($row)->setRowHeight(44);

        $row++;

        $bahanBakus = $this->productionBatch->bahanBakus;

        if ($bahanBakus->isEmpty()) {
            $this->emptyBlockRow($sheet, $row, 'A', 'P');

            return $row + 2;
        }

        foreach ($bahanBakus as $bahanBaku) {
            $values = [
                $bahanBaku->no_po ?? $this->productionBatch->no_po ?? '-',
                $bahanBaku->cost_center_name ?? $this->productionBatch->cost_center_name ?? '-',
                $bahanBaku->material->material_description ?? '-',
                $bahanBaku->jumlah_bag ?? '-',
                $this->number($bahanBaku->jumlah_kg) . ' Kg',
            ];

            foreach ($values as $i => $value) {
                [$start, $end] = $spans[$i];

                if ($start !== $end) {
                    $sheet->mergeCells("{$start}{$row}:{$end}{$row}");
                }

                $sheet->setCellValue("{$start}{$row}", $value);
            }

            $this->styleRow($sheet, $row, 12, false);
            $sheet->getRowDimension($row)->setRowHeight(32);

            $row++;
        }

        $totalKg = $bahanBakus->sum(
            fn ($item) => (float) ($item->jumlah_kg ?? 0)
        );

        $sheet->mergeCells("A{$row}:M{$row}");
        $sheet->setCellValue("A{$row}", 'TOTAL');

        $sheet->mergeCells("N{$row}:P{$row}");
        $sheet->setCellValue("N{$row}", $this->number($totalKg) . ' Kg');

        $sheet->getStyle("A{$row}:P{$row}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle("A{$row}:P{$row}")
            ->getFont()
            ->setBold(true)
            ->setSize(13);

        $sheet->getStyle("A{$row}:L{$row}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("M{$row}:P{$row}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getRowDimension($row)->setRowHeight(34);

        return $row + 2;
    }

    protected function buildDataHasilProduksi($sheet, int $row): int
    {
        $this->sectionTitle(
            $sheet,
            $row,
            'DATA PRODUK & HASIL PRODUKSI',
            'A',
            'P'
        );

        $row++;

        $columns = [
            'No. PO',
            'Cost Center Name',
            'Material',
            'Domestik Kg',
            'Further Kg',
            'Frozen Kg',
            'RM',
            'Total Bag',
            'Total Kg',
            'Yield %',
            'Loss Kg',
            'Loss %',
            'Pickup Kg',
            'Pickup %',
            'Cek Suhu Jam / Suhu',
            'Packaging',
        ];

        $spans = $this->distributeColumns(count($columns));

        foreach ($columns as $i => $label) {
            [$start, $end] = $spans[$i];

            if ($start !== $end) {
                $sheet->mergeCells("{$start}{$row}:{$end}{$row}");
            }

            $sheet->setCellValue("{$start}{$row}", $label);
        }

        $this->styleRow($sheet, $row, 14, true);
        $sheet->getRowDimension($row)->setRowHeight(58);

        $row++;

        $dataHasilProduksis = $this->productionBatch->dataHasilProduksis;

        if ($dataHasilProduksis->isEmpty()) {
            $this->emptyBlockRow($sheet, $row, 'A', 'P');

            return $row + 2;
        }

        foreach ($dataHasilProduksis as $item) {
            $values = [
                $item->no_po ?? $this->productionBatch->no_po ?? '-',
                $item->cost_center_name ?? $this->productionBatch->cost_center_name ?? '-',
                $item->material->material_description ?? '-',
                $this->number($item->domestik_kg),
                $this->number($item->further_kg),
                $this->number($item->frozen_kg),
                $this->number($item->rm),
                $item->total_bag ?? '-',
                $this->number($item->total_kg),
                $this->number($item->yield_persen) . ' %',
                $this->number($item->loss_kg),
                $this->number($item->loss_persen) . ' %',
                $this->number($item->pickup_kg),
                $this->number($item->pickup_persen) . ' %',
                ($item->pengecekan_suhu_jam ?? '-') . ' / ' .
                    $this->number($item->pengecekan_suhu_suhu),
                ($item->packaging_nama_kemasan ?? '-') . ' (' .
                    $this->number($item->packaging_jumlah_kg) . ' Kg)',
            ];

            foreach ($values as $i => $value) {
                [$start, $end] = $spans[$i];

                if ($start !== $end) {
                    $sheet->mergeCells("{$start}{$row}:{$end}{$row}");
                }

                $sheet->setCellValue("{$start}{$row}", $value);
            }

            $this->styleRow($sheet, $row, 12, false);
            $sheet->getRowDimension($row)->setRowHeight(42);

            $row++;
        }

        return $row + 1;
    }

    protected function buildProcessTable(
        $sheet,
        int $row,
        string $title,
        array $columns,
        array $rows
    ): int {
        $this->sectionTitle($sheet, $row, $title, 'A', 'P');

        $row++;

        $spans = $this->distributeColumns(count($columns));

        foreach ($columns as $i => $label) {
            [$start, $end] = $spans[$i];

            if ($start !== $end) {
                $sheet->mergeCells("{$start}{$row}:{$end}{$row}");
            }

            $sheet->setCellValue("{$start}{$row}", $label);
        }

        $this->styleRow($sheet, $row, 14, true);
        $sheet->getRowDimension($row)->setRowHeight(52);

        $row++;

        if (empty($rows)) {
            $this->emptyBlockRow($sheet, $row, 'A', 'P');

            return $row + 2;
        }

        foreach ($rows as $record) {
            foreach ($record as $i => $value) {
                [$start, $end] = $spans[$i];

                if ($start !== $end) {
                    $sheet->mergeCells("{$start}{$row}:{$end}{$row}");
                }

                $sheet->setCellValue("{$start}{$row}", $value);
            }

            $this->styleRow($sheet, $row, 12, false);
            $sheet->getRowDimension($row)->setRowHeight(38);

            $row++;
        }

        return $row + 1;
    }

    protected function distributeColumns(int $n): array
    {
        $letters = [];

        for ($i = 1; $i <= 16; $i++) {
            $letters[] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
        }

        $n = max(1, min($n, count($letters)));

        $base = intdiv(count($letters), $n);
        $extra = count($letters) % $n;
        $cursor = 0;
        $spans = [];

        for ($i = 0; $i < $n; $i++) {
            $width = $base + ($i < $extra ? 1 : 0);

            $start = $letters[$cursor];
            $end = $letters[$cursor + $width - 1];

            $spans[] = [$start, $end];

            $cursor += $width;
        }

        return $spans;
    }

    protected function styleRow($sheet, int $row, int $size, bool $header): void
    {
        $range = "A{$row}:P{$row}";

        $sheet->getStyle($range)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle($range)
            ->getFont()
            ->setSize($size)
            ->setBold($header);

        $sheet->getStyle($range)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        if ($header) {
            $sheet->getStyle($range)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('E5E5E5');
        }
    }

    protected function emptyBlockRow(
        $sheet,
        int $row,
        string $startCol,
        string $endCol
    ): void {
        $sheet->mergeCells("{$startCol}{$row}:{$endCol}{$row}");
        $sheet->setCellValue("{$startCol}{$row}", 'Tidak ada data');

        $sheet->getStyle("{$startCol}{$row}:{$endCol}{$row}")
            ->getFont()
            ->setItalic(true)
            ->setBold(true)
            ->setSize(13);

        $sheet->getStyle("{$startCol}{$row}:{$endCol}{$row}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        $sheet->getStyle("{$startCol}{$row}:{$endCol}{$row}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getRowDimension($row)->setRowHeight(38);
    }

    protected function buildApproval($sheet, int $row): void
    {
        $cols = [
            ['A', 'D', 'Dibuat Oleh', 'Operator'],
            ['E', 'H', 'Diperiksa Oleh', 'QC'],
            ['I', 'L', 'Diketahui Oleh', 'Foreman/Lady'],
            ['M', 'P', 'Disetujui Oleh', 'Supervisor'],
        ];

        foreach ($cols as [$start, $end, $title, $role]) {
            $sheet->mergeCells("{$start}{$row}:{$end}{$row}");
            $sheet->setCellValue("{$start}{$row}", $title);

            $sheet->getStyle("{$start}{$row}:{$end}{$row}")
                ->getFont()
                ->setBold(true)
                ->setSize(12);

            $sheet->getStyle("{$start}{$row}:{$end}{$row}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
        }

        $sheet->getRowDimension($row)->setRowHeight(30);

        $row++;
        $endTtdRow = $row + 3;

        foreach ($cols as [$start, $end, $title, $role]) {
            $sheet->mergeCells("{$start}{$row}:{$end}{$endTtdRow}");

            $sheet->getStyle("{$start}{$row}:{$end}{$endTtdRow}")
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
        }

        for ($i = $row; $i <= $endTtdRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(28);
        }

        $row = $endTtdRow + 1;

        foreach ($cols as [$start, $end, $title, $role]) {
            $sheet->mergeCells("{$start}{$row}:{$end}{$row}");
            $sheet->setCellValue("{$start}{$row}", $role);

            $sheet->getStyle("{$start}{$row}:{$end}{$row}")
                ->getFont()
                ->setBold(true)
                ->setSize(12);

            $sheet->getStyle("{$start}{$row}:{$end}{$row}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->getStyle("{$start}{$row}:{$end}{$row}")
                ->getBorders()
                ->getTop()
                ->setBorderStyle(Border::BORDER_THIN);
        }

        $sheet->getRowDimension($row)->setRowHeight(30);
    }

    protected function sectionTitle(
        $sheet,
        int $row,
        string $title,
        string $startCol,
        string $endCol
    ): void {
        $sheet->mergeCells("{$startCol}{$row}:{$endCol}{$row}");
        $sheet->setCellValue("{$startCol}{$row}", $title);

        $sheet->getStyle("{$startCol}{$row}:{$endCol}{$row}")
            ->getFont()
            ->setBold(true)
            ->setSize(16);

        $sheet->getStyle("{$startCol}{$row}:{$endCol}{$row}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        $sheet->getStyle("{$startCol}{$row}:{$endCol}{$row}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getRowDimension($row)->setRowHeight(40);
    }

    protected function number($value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        return number_format((float) $value, 2, ',', '.');
    }

    protected function time($value): string
    {
        if (!$value) {
            return '-';
        }

        return substr((string) $value, 0, 5);
    }
}