<?php

namespace App\Exports;

use App\Models\ProductionBatch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class LaporanPengendalianProdukTengahSheet implements
    FromCollection,
    WithColumnWidths,
    WithEvents,
    WithTitle
{
    protected ProductionBatch $productionBatch;

    // Sama seperti Lembar Depan: A:R (18 kolom) karena tabel Rekapitulasi &
    // Productivity punya Bahan Baku + Total KG di depan.
    protected string $lastCol = 'R';
    protected int $totalCols = 18;

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
        return 'Lembar Tengah';
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
            'Q' => 15,
            'R' => 15,
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

                // Di-sort per bahan_baku_id supaya baris dengan bahan baku
                // yang sama berurutan (syarat merge vertikal).
                $rekapitulasiProduksis = $this->productionBatch->rekapitulasiProduksis
                    ->sortBy('bahan_baku_id')
                    ->values();

                $row = $this->buildProcessTable(
                    $sheet,
                    $row,
                    'REKAPITULASI PRODUKSI & MONITORING MESIN',
                    [
                        'Bahan Baku',
                        'Total KG',
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
                    $rekapitulasiProduksis
                        ->map(fn ($item) => [
                            $item->bahanBaku?->material?->material_description ?? '-',
                            $item->bahanBaku
                                ? $this->number($item->bahanBaku->jumlah_kg) . ' Kg'
                                : '-',
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
                        ->all(),
                    [0, 1],
                    $rekapitulasiProduksis->pluck('bahan_baku_id')->values()->all()
                );

                $productivities = $this->productionBatch->productivities
                    ->sortBy('bahan_baku_id')
                    ->values();

                $row = $this->buildProcessTable(
                    $sheet,
                    $row,
                    'PRODUCTIVITY',
                    [
                        'Bahan Baku',
                        'Total KG',
                        'Material',
                        'Worker Indirect',
                        'Worker Direct',
                        'Worker Total',
                        'Total Weight',
                        'Total Time',
                        'Productivity Indirect',
                        'Productivity Direct',
                    ],
                    $productivities
                        ->map(fn ($item) => [
                            $item->bahanBaku?->material?->material_description ?? '-',
                            $item->bahanBaku
                                ? $this->number($item->bahanBaku->jumlah_kg) . ' Kg'
                                : '-',
                            $item->material?->material_description ?? '-',
                            $item->worker_indirect ?? '-',
                            $item->worker_direct ?? '-',
                            $item->worker_total ?? '-',
                            $this->number($item->total_weight),
                            $this->number($item->total_time),
                            $this->number($item->productivity_indirect),
                            $this->number($item->productivity_direct),
                        ])
                        ->values()
                        ->all(),
                    [0, 1],
                    $productivities->pluck('bahan_baku_id')->values()->all()
                );

                $this->buildApproval($sheet, $row);

                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle("A1:{$this->lastCol}{$lastRow}")
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                $sheet->getDefaultRowDimension()->setRowHeight(28);
                $sheet->setShowGridlines(false);

                // Print area eksplisit biar fit-to-width tidak menghitung
                // ruang kosong di luar tabel saat halaman di-center.
                $sheet->getPageSetup()
                    ->setPrintArea("A1:{$this->lastCol}{$lastRow}");
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
            ->setFitToHeight(0)
            ->setHorizontalCentered(true);

        $sheet->getPageMargins()
            ->setTop(0.25)
            ->setRight(0.15)
            ->setBottom(0.25)
            ->setLeft(0.15);

        $sheet->getSheetView()->setZoomScale(75);
    }

    protected function buildHeader($sheet): void
    {
        $sheet->mergeCells("A1:{$this->lastCol}1");
        $sheet->setCellValue('A1', 'PT CHAROEN POKPHAND INDONESIA');

        $sheet->mergeCells("A2:{$this->lastCol}2");
        $sheet->setCellValue('A2', 'FOOD DIVISION');

        $sheet->mergeCells("A3:{$this->lastCol}3");
        $sheet->setCellValue('A3', 'LAPORAN PENGENDALIAN PRODUK');

        $tanggal = $this->productionBatch->tanggal
            ? $this->productionBatch->tanggal->format('d/m/Y')
            : '-';

        $sheet->mergeCells("A4:{$this->lastCol}4");
        $sheet->setCellValue(
            'A4',
            'PRODUCTION BATCH : ' . $tanggal .
            ' - SHIFT ' . ($this->productionBatch->shift ?? '-')
        );

        $sheet->getStyle("A1:{$this->lastCol}4")
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

    /**
     * Render tabel proses generik (Rekapitulasi, Productivity).
     *
     * $mergeColumns: index kolom (0-based) yang di-merge vertikal untuk baris
     * berurutan dengan key yang sama.
     * $mergeKeys: nilai pembanding per baris (sejajar dengan $rows), biasanya
     * bahan_baku_id. Key null dianggap unik (tidak ikut ter-merge).
     */
    protected function buildProcessTable(
        $sheet,
        int $row,
        string $title,
        array $columns,
        array $rows,
        array $mergeColumns = [],
        array $mergeKeys = []
    ): int {
        $this->sectionTitle($sheet, $row, $title, 'A', $this->lastCol);
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
            $this->emptyBlockRow($sheet, $row, 'A', $this->lastCol);

            return $row + 2;
        }

        $hasMerge = !empty($mergeColumns);
        $totalRows = count($rows);

        $keys = [];
        foreach (array_keys($rows) as $index) {
            $keys[$index] = $mergeKeys[$index] ?? ('__row_' . $index);
        }

        $groupStartRow = $row;

        foreach ($rows as $index => $record) {
            $isNewGroup = !$hasMerge || $index === 0 || $keys[$index] !== $keys[$index - 1];

            if ($isNewGroup) {
                $groupStartRow = $row;
            }

            foreach ($record as $i => $value) {
                [$start, $end] = $spans[$i];

                // Kolom merge vertikal: isi nilai cuma di baris pertama grup,
                // merge dilakukan sekali di akhir grup (hindari merge tumpang tindih).
                if ($hasMerge && in_array($i, $mergeColumns, true)) {
                    if ($isNewGroup) {
                        $sheet->setCellValue("{$start}{$row}", $value);
                    }

                    continue;
                }

                if ($start !== $end) {
                    $sheet->mergeCells("{$start}{$row}:{$end}{$row}");
                }

                $sheet->setCellValue("{$start}{$row}", $value);
            }

            $this->styleRow($sheet, $row, 12, false);
            $sheet->getRowDimension($row)->setRowHeight(38);

            $isLastInGroup = $hasMerge
                && ($index === $totalRows - 1 || $keys[$index + 1] !== $keys[$index]);

            if ($isLastInGroup) {
                foreach ($mergeColumns as $colIndex) {
                    [$start, $end] = $spans[$colIndex];

                    if ($start !== $end || $row > $groupStartRow) {
                        $sheet->mergeCells("{$start}{$groupStartRow}:{$end}{$row}");
                    }

                    $sheet->getStyle("{$start}{$groupStartRow}:{$end}{$row}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                        ->setVertical(Alignment::VERTICAL_CENTER)
                        ->setWrapText(true);
                }
            }

            $row++;
        }

        return $row + 1;
    }

    protected function distributeColumns(int $n): array
    {
        $letters = [];

        for ($i = 1; $i <= $this->totalCols; $i++) {
            $letters[] = Coordinate::stringFromColumnIndex($i);
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
        $range = "A{$row}:{$this->lastCol}{$row}";

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
            ['A', 'E', 'Dibuat Oleh', 'Operator'],
            ['F', 'I', 'Diperiksa Oleh', 'QC'],
            ['J', 'N', 'Diketahui Oleh', 'Foreman/Lady'],
            ['O', 'R', 'Disetujui Oleh', 'Supervisor'],
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