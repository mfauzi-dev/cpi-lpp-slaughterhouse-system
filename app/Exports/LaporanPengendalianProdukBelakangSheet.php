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

class LaporanPengendalianProdukBelakangSheet implements
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
        return 'Lembar Belakang';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 24,
            'C' => 14,
            'D' => 12,
            'E' => 12,
            'F' => 12,
            'G' => 12,
            'H' => 12,
            'I' => 12,
            'J' => 12,
            'K' => 12,
            'L' => 12,
            'M' => 14,
            'N' => 14,
            'O' => 24,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $this->setupPage($sheet);
                $this->buildHeader($sheet);

                $row = 10;
                $row = $this->buildMetalDetector($sheet, $row);
                $row = $this->buildLegend($sheet, $row);

                $this->buildApproval($sheet, $row + 2);

                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle("A1:O{$lastRow}")
                    ->getFont()
                    ->setName('Calibri');

                $sheet->getStyle("A1:O{$lastRow}")
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                $sheet->getDefaultRowDimension()->setRowHeight(25);
                $sheet->setShowGridlines(false);
            },
        ];
    }

    protected function setupPage($sheet): void
    {
        $sheet->getPageSetup()
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
        $sheet->mergeCells('A1:O1');
        $sheet->setCellValue('A1', 'PT CHAROEN POKPHAND INDONESIA');

        $sheet->mergeCells('A2:O2');
        $sheet->setCellValue('A2', 'FOOD DIVISION');

        $sheet->mergeCells('A4:O4');
        $sheet->setCellValue('A4', 'LAPORAN PENGENDALIAN PRODUK');

        $sheet->mergeCells('A5:O5');
        $sheet->setCellValue('A5', 'METAL DETECTOR');

        $tanggal = $this->productionBatch->tanggal
            ? $this->productionBatch->tanggal->format('d/m/Y')
            : '-';

        $sheet->mergeCells('A8:C8');
        $sheet->setCellValue('A8', 'Hari/Tanggal:');

        $sheet->mergeCells('D8:F8');
        $sheet->setCellValue('D8', $tanggal);

        $sheet->mergeCells('J8:K8');
        $sheet->setCellValue('J8', 'Shift:');

        $sheet->mergeCells('L8:O8');
        $sheet->setCellValue(
            'L8',
            $this->productionBatch->shift ?? '-'
        );

        $sheet->getStyle('A1:O5')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        $sheet->getStyle('A8:O8')
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        $sheet->getStyle('A1:O2')
            ->getFont()
            ->setName('Calibri')
            ->setItalic(true)
            ->setSize(12);

        $sheet->getStyle('A4:O5')
            ->getFont()
            ->setName('Calibri')
            ->setBold(true)
            ->setSize(16);

        $sheet->getStyle('A8:O8')
            ->getFont()
            ->setName('Calibri')
            ->setBold(true)
            ->setSize(14);

        $sheet->getStyle('D8:F8')
            ->getBorders()
            ->getBottom()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle('L8:O8')
            ->getBorders()
            ->getBottom()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getRowDimension(1)->setRowHeight(26);
        $sheet->getRowDimension(2)->setRowHeight(26);
        $sheet->getRowDimension(3)->setRowHeight(10);
        $sheet->getRowDimension(4)->setRowHeight(34);
        $sheet->getRowDimension(5)->setRowHeight(34);
        $sheet->getRowDimension(6)->setRowHeight(12);
        $sheet->getRowDimension(7)->setRowHeight(12);
        $sheet->getRowDimension(8)->setRowHeight(30);
        $sheet->getRowDimension(9)->setRowHeight(12);
    }

    protected function buildMetalDetector($sheet, int $row): int
    {
        $topRow = $row;
        $groupRow = $row + 1;
        $sizeRow = $row + 2;
        $subRow = $row + 3;

        $verticalHeaders = [
            'A' => 'Pukul',
            'B' => 'Nama Produk',
            'C' => 'No. Program',
            'M' => 'In',
            'N' => 'Out',
            'O' => 'Keterangan',
        ];

        foreach ($verticalHeaders as $col => $label) {
            $sheet->mergeCells("{$col}{$topRow}:{$col}{$subRow}");
            $sheet->setCellValue("{$col}{$topRow}", $label);
        }

        $sheet->mergeCells("D{$topRow}:L{$topRow}");
        $sheet->setCellValue("D{$topRow}", 'Standar Spesimen');

        $groups = [
            ['D', 'F', 'Fe', '3.0 mm'],
            ['G', 'I', 'Non-Fe', '3.5 mm'],
            ['J', 'L', 'SUS 316', '4.5 mm'],
        ];

        foreach ($groups as [$start, $end, $label, $size]) {
            $sheet->mergeCells("{$start}{$groupRow}:{$end}{$groupRow}");
            $sheet->setCellValue("{$start}{$groupRow}", $label);

            $sheet->mergeCells("{$start}{$sizeRow}:{$end}{$sizeRow}");
            $sheet->setCellValue("{$start}{$sizeRow}", $size);
        }

        $subLabels = [
            'D' => 'Di depan produk',
            'E' => 'Di tengah produk',
            'F' => 'Di belakang produk',
            'G' => 'Di depan produk',
            'H' => 'Di tengah produk',
            'I' => 'Di belakang produk',
            'J' => 'Di depan produk',
            'K' => 'Di tengah produk',
            'L' => 'Di belakang produk',
        ];

        foreach ($subLabels as $col => $label) {
            $sheet->setCellValue("{$col}{$subRow}", $label);
        }

        $this->styleRow($sheet, $topRow, 14, true);
        $this->styleRow($sheet, $groupRow, 14, true);
        $this->styleRow($sheet, $sizeRow, 14, true);
        $this->styleRow($sheet, $subRow, 12, true);

        $sheet->getStyle("A{$topRow}:O{$subRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        $sheet->getRowDimension($topRow)->setRowHeight(28);
        $sheet->getRowDimension($groupRow)->setRowHeight(28);
        $sheet->getRowDimension($sizeRow)->setRowHeight(28);
        $sheet->getRowDimension($subRow)->setRowHeight(52);

        $row = $subRow + 1;
        $metalDetectors = $this->productionBatch->metalDetectors;

        if ($metalDetectors->isEmpty()) {
            $this->emptyBlockRow($sheet, $row, 'A', 'O');

            return $row + 2;
        }

        $statusCols = ['D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];

        $statusFields = [
            'fe_depan',
            'fe_tengah',
            'fe_belakang',
            'non_fe_depan',
            'non_fe_tengah',
            'non_fe_belakang',
            'sus_316_depan',
            'sus_316_tengah',
            'sus_316_belakang',
        ];

        foreach ($metalDetectors as $item) {
            $waktu = $item->created_at
                ? $item->created_at->format('H:i')
                : '-';

            $sheet->setCellValue("A{$row}", $waktu);
            $sheet->setCellValue(
                "B{$row}",
                $item->material->material_description ?? '-'
            );
            $sheet->setCellValue("C{$row}", $item->no_program ?? '-');

            foreach ($statusCols as $i => $col) {
                $value = $item->{$statusFields[$i]} ?? null;

                $sheet->setCellValue("{$col}{$row}", $value ?? '-');
                $this->styleStatusCell($sheet, "{$col}{$row}", $value);
            }

            $sheet->setCellValue("M{$row}", $item->in ?? '-');
            $sheet->setCellValue("N{$row}", $item->out ?? '-');
            $sheet->setCellValue("O{$row}", $item->keterangan ?? '-');

            $this->styleRow($sheet, $row, 12, false);
            $sheet->getRowDimension($row)->setRowHeight(42);

            $row++;
        }

        return $row + 1;
    }

    protected function styleStatusCell($sheet, string $cell, ?string $value): void
    {
        if ($value === 'Terdeteksi') {
            $sheet->getStyle($cell)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('C6EFCE');

            $sheet->getStyle($cell)
                ->getFont()
                ->getColor()
                ->setRGB('006100');
        } elseif ($value === 'Tidak terdeteksi') {
            $sheet->getStyle($cell)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('FFC7CE');

            $sheet->getStyle($cell)
                ->getFont()
                ->getColor()
                ->setRGB('9C0006');
        }
    }

    protected function buildLegend($sheet, int $row): int
    {
        $sheet->mergeCells("A{$row}:O{$row}");
        $sheet->setCellValue(
            "A{$row}",
            'Keterangan: Hijau = Terdeteksi, Merah = Tidak terdeteksi.'
        );

        $sheet->getStyle("A{$row}:O{$row}")
            ->getFont()
            ->setName('Calibri')
            ->setItalic(true)
            ->setSize(12);

        $sheet->getStyle("A{$row}:O{$row}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        $sheet->getRowDimension($row)->setRowHeight(28);

        return $row + 1;
    }

    protected function buildApproval($sheet, int $row): void
    {
        $cols = [
            ['A', 'C', 'Dibuat Oleh', 'Operator'],
            ['D', 'G', 'Diperiksa Oleh', 'QC'],
            ['H', 'K', 'Diketahui Oleh', 'Foreman/Lady'],
            ['L', 'O', 'Disetujui Oleh', 'Supervisor'],
        ];

        foreach ($cols as [$start, $end, $title, $role]) {
            $sheet->mergeCells("{$start}{$row}:{$end}{$row}");
            $sheet->setCellValue("{$start}{$row}", $title);

            $sheet->getStyle("{$start}{$row}:{$end}{$row}")
                ->getFont()
                ->setName('Calibri')
                ->setBold(true)
                ->setSize(14);

            $sheet->getStyle("{$start}{$row}:{$end}{$row}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
        }

        $sheet->getRowDimension($row)->setRowHeight(32);

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
                ->setName('Calibri')
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

    protected function styleRow($sheet, int $row, int $size, bool $header): void
    {
        $range = "A{$row}:O{$row}";

        $sheet->getStyle($range)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle($range)
            ->getFont()
            ->setName('Calibri')
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
            ->setName('Calibri')
            ->setItalic(true)
            ->setBold(true)
            ->setSize(14);

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
}