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
            'C' => 15,
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
            ->setTop(0.15)
            ->setRight(0.15)
            ->setBottom(0.15)
            ->setLeft(0.15);

        $sheet->getSheetView()->setZoomScale(85);
    }

    protected function buildHeader($sheet): void
    {
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A1', 'PT CHAROEN POKPHAND INDONESIA');

        $sheet->mergeCells('A2:N2');
        $sheet->setCellValue('A2', 'FOOD DIVISION');

        $sheet->mergeCells('A3:N3');
        $sheet->setCellValue('A3', 'LAPORAN PENGENDALIAN PRODUK');

        $tanggal = $this->productionBatch->tanggal
            ? $this->productionBatch->tanggal->format('d/m/Y')
            : '-';

        $sheet->mergeCells('A4:N4');
        $sheet->setCellValue(
            'A4',
            'PRODUCTION BATCH : ' . $tanggal . ' - SHIFT ' . ($this->productionBatch->shift ?? '-')
        );

        $sheet->getStyle('A1:N4')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(14);

        $sheet->getRowDimension(1)->setRowHeight(32);
        $sheet->getRowDimension(2)->setRowHeight(28);
        $sheet->getRowDimension(3)->setRowHeight(34);
        $sheet->getRowDimension(4)->setRowHeight(30);
    }

    /**
     * Info produksi, format pasangan label:value, 2 kolom (kiri & kanan), 4 baris:
     * Tanggal - Absen Worker
     * Shift - Tanggal Transfer
     * Conveyor - Tanggal Proses
     * No. PO - Cost Center Name
     * Tanpa border, font 12.
     */
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
            [
                'No. PO',
                $pb->no_po ?? '-',
                'Cost Center Name',
                $pb->cost_center_name ?? '-',
            ],
        ];

        // Lebar penuh (14 kolom, A..N) dibagi: label1(3) value1(4) label2(3) value2(4)
        $labelCol1Start = 'A'; $labelCol1End = 'C';
        $valueCol1Start = 'D'; $valueCol1End = 'G';
        $labelCol2Start = 'H'; $labelCol2End = 'J';
        $valueCol2Start = 'K'; $valueCol2End = 'N';

        foreach ($pairs as $i => [$label1, $value1, $label2, $value2]) {
            $r = $row + $i;

            $sheet->mergeCells("{$labelCol1Start}{$r}:{$labelCol1End}{$r}");
            $sheet->setCellValue("{$labelCol1Start}{$r}", $label1 . ' :');

            $sheet->mergeCells("{$valueCol1Start}{$r}:{$valueCol1End}{$r}");
            $sheet->setCellValue("{$valueCol1Start}{$r}", $value1);

            $sheet->mergeCells("{$labelCol2Start}{$r}:{$labelCol2End}{$r}");
            $sheet->setCellValue("{$labelCol2Start}{$r}", $label2 . ' :');

            $sheet->mergeCells("{$valueCol2Start}{$r}:{$valueCol2End}{$r}");
            $sheet->setCellValue("{$valueCol2Start}{$r}", $value2);

            $sheet->getStyle("{$labelCol1Start}{$r}:{$labelCol1End}{$r}")
                ->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle("{$labelCol1Start}{$r}:{$labelCol1End}{$r}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->getStyle("{$valueCol1Start}{$r}:{$valueCol1End}{$r}")
                ->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle("{$valueCol1Start}{$r}:{$valueCol1End}{$r}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->getStyle("{$labelCol2Start}{$r}:{$labelCol2End}{$r}")
                ->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle("{$labelCol2Start}{$r}:{$labelCol2End}{$r}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->getStyle("{$valueCol2Start}{$r}:{$valueCol2End}{$r}")
                ->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle("{$valueCol2Start}{$r}:{$valueCol2End}{$r}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->getRowDimension($r)->setRowHeight(26);
        }

        return $row + count($pairs) + 1;
    }

    protected function buildBahanBaku($sheet, int $row): int
    {
        $this->sectionTitle($sheet, $row, 'A. DATA BAHAN BAKU', 'A', 'N');

        $row++;

        $spans = [
            ['A', 'F'],
            ['G', 'J'],
            ['K', 'N'],
        ];

        $headers = ['MATERIAL', 'JUMLAH BAG', 'JUMLAH KG'];

        foreach ($headers as $i => $label) {
            [$c1, $c2] = $spans[$i];

            $sheet->mergeCells("{$c1}{$row}:{$c2}{$row}");
            $sheet->setCellValue("{$c1}{$row}", $label);
        }

        $this->styleRow($sheet, $row, 12, true);

        $sheet->getRowDimension($row)->setRowHeight(28);

        $row++;

        $bahanBakus = $this->productionBatch->bahanBakus;

        if ($bahanBakus->isEmpty()) {
            $this->emptyBlockRow($sheet, $row, 'A', 'N');

            $row++;

            return $row + 1;
        }

        foreach ($bahanBakus as $bahanBaku) {
            $values = [
                $bahanBaku->material->material_description ?? '-',
                $bahanBaku->jumlah_bag ?? '-',
                $this->number($bahanBaku->jumlah_kg) . ' Kg',
            ];

            foreach ($values as $i => $value) {
                [$c1, $c2] = $spans[$i];

                $sheet->mergeCells("{$c1}{$row}:{$c2}{$row}");
                $sheet->setCellValue("{$c1}{$row}", $value);
            }

            $this->styleRow($sheet, $row, 12, false);

            $sheet->getRowDimension($row)->setRowHeight(24);

            $row++;
        }

        $totalKg = $bahanBakus->sum(fn ($b) => (float) ($b->jumlah_kg ?? 0));

        $sheet->mergeCells("A{$row}:J{$row}");
        $sheet->setCellValue("A{$row}", 'TOTAL');

        $sheet->mergeCells("K{$row}:N{$row}");
        $sheet->setCellValue("K{$row}", $this->number($totalKg) . ' Kg');

        $sheet->getStyle("A{$row}:N{$row}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle("A{$row}:N{$row}")
            ->getFont()
            ->setBold(true)
            ->setSize(13);

        $sheet->getStyle("A{$row}:J{$row}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("K{$row}:N{$row}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getRowDimension($row)->setRowHeight(28);

        $row++;

        return $row + 1;
    }

    protected function buildDataHasilProduksi($sheet, int $row): int
    {
        $this->sectionTitle($sheet, $row, 'B. DATA PRODUK & HASIL PRODUKSI', 'A', 'N');

        $row++;

        $columns = [
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
            [$c1, $c2] = $spans[$i];

            if ($c1 !== $c2) {
                $sheet->mergeCells("{$c1}{$row}:{$c2}{$row}");
            }

            $sheet->setCellValue("{$c1}{$row}", $label);
        }

        $this->styleRow($sheet, $row, 12, true);

        $sheet->getRowDimension($row)->setRowHeight(30);

        $row++;

        $dataHasilProduksis = $this->productionBatch->dataHasilProduksis;

        if ($dataHasilProduksis->isEmpty()) {
            $this->emptyBlockRow($sheet, $row, 'A', 'N');

            $row++;

            return $row + 1;
        }

        foreach ($dataHasilProduksis as $item) {
            $values = [
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
                ($item->pengecekan_suhu_jam ?? '-') . ' / ' . $this->number($item->pengecekan_suhu_suhu),
                ($item->packaging_nama_kemasan ?? '-') . ' (' . $this->number($item->packaging_jumlah_kg) . ' Kg)',
            ];

            foreach ($values as $i => $value) {
                [$c1, $c2] = $spans[$i];

                if ($c1 !== $c2) {
                    $sheet->mergeCells("{$c1}{$row}:{$c2}{$row}");
                }

                $sheet->setCellValue("{$c1}{$row}", $value);
            }

            $this->styleRow($sheet, $row, 12, false);

            $sheet->getRowDimension($row)->setRowHeight(26);

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
        $this->sectionTitle($sheet, $row, $title, 'A', 'N');

        $row++;

        $spans = $this->distributeColumns(count($columns));

        foreach ($columns as $i => $label) {
            [$c1, $c2] = $spans[$i];

            if ($c1 !== $c2) {
                $sheet->mergeCells("{$c1}{$row}:{$c2}{$row}");
            }

            $sheet->setCellValue("{$c1}{$row}", $label);
        }

        $this->styleRow($sheet, $row, 11, true);

        $sheet->getRowDimension($row)->setRowHeight(28);

        $row++;

        if (empty($rows)) {
            $this->emptyBlockRow($sheet, $row, 'A', 'N');

            $row++;

            return $row + 1;
        }

        foreach ($rows as $record) {
            foreach ($record as $i => $value) {
                [$c1, $c2] = $spans[$i];

                if ($c1 !== $c2) {
                    $sheet->mergeCells("{$c1}{$row}:{$c2}{$row}");
                }

                $sheet->setCellValue("{$c1}{$row}", $value);
            }

            $this->styleRow($sheet, $row, 11, false);

            $sheet->getRowDimension($row)->setRowHeight(26);

            $row++;
        }

        return $row + 1;
    }

    protected function distributeColumns(int $n): array
    {
        $letters = range('A', 'N');

        $n = max(1, min($n, 14));

        $base = intdiv(14, $n);
        $extra = 14 % $n;
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
        $range = "A{$row}:N{$row}";

        $sheet->getStyle($range)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle($range)
            ->getFont()
            ->setBold(true)
            ->setSize($size);

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
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("{$startCol}{$row}:{$endCol}{$row}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getRowDimension($row)->setRowHeight(30);
    }


    protected function buildApproval($sheet, int $row): void
    {
        $cols = [
            ['A', 'C', 'Dibuat Oleh', 'Operator'],
            ['D', 'G', 'Diperiksa Oleh', 'QC'],
            ['H', 'J', 'Diketahui Oleh', 'Foreman/Lady'],
            ['K', 'N', 'Disetujui Oleh', 'Supervisor'],
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

        $sheet->getRowDimension($row)->setRowHeight(24);

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
            $sheet->getRowDimension($i)->setRowHeight(24);
        }

        $row = $endTtdRow + 1;

        foreach ($cols as [$start, $end, $title, $role]) {
            $sheet->mergeCells("{$start}{$row}:{$end}{$row}");
            $sheet->setCellValue("{$start}{$row}", $role);

            $sheet->getStyle("{$start}{$row}:{$end}{$row}")
                ->getFont()
                ->setBold(true)
                ->setSize(11);

            $sheet->getStyle("{$start}{$row}:{$end}{$row}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->getStyle("{$start}{$row}:{$end}{$row}")
                ->getBorders()
                ->getTop()
                ->setBorderStyle(Border::BORDER_THIN);
        }

        $sheet->getRowDimension($row)->setRowHeight(24);
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
            ->setSize(15);

        $sheet->getStyle("{$startCol}{$row}:{$endCol}{$row}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("{$startCol}{$row}:{$endCol}{$row}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getRowDimension($row)->setRowHeight(30);
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