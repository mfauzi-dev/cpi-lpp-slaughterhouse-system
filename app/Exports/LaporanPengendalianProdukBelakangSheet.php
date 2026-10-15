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
            'A' => 22, // Nama Produk
            'B' => 14, // No. Program
            'C' => 11, // Fe - Depan
            'D' => 11, // Fe - Tengah
            'E' => 11, // Fe - Belakang
            'F' => 11, // Non-Fe - Depan
            'G' => 11, // Non-Fe - Tengah
            'H' => 11, // Non-Fe - Belakang
            'I' => 11, // SUS 316 - Depan
            'J' => 11, // SUS 316 - Tengah
            'K' => 11, // SUS 316 - Belakang
            'L' => 14, // In
            'M' => 14, // Out
            'N' => 22, // Keterangan
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

                $row = $this->buildMetalDetector($sheet, $row);

                $this->buildLegend($sheet, $row);
                $this->buildApproval($sheet, $row + 2);

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
        $sheet->setCellValue('A3', 'LAPORAN PENGENDALIAN PRODUK METAL DETECTOR');

        $tanggal = $this->productionBatch->tanggal
            ? $this->productionBatch->tanggal->format('d/m/Y')
            : '-';

        $sheet->mergeCells('A4:G4');
        $sheet->setCellValue('A4', 'Hari/Tanggal : ' . $tanggal);

        $sheet->mergeCells('H4:N4');
        $sheet->setCellValue('H4', 'Shift : ' . ($this->productionBatch->shift ?? '-'));

        $sheet->getStyle('A1:N3')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle('A4:N4')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(15);
        $sheet->getStyle('A4:N4')->getFont()->setBold(true)->setSize(12);

        $sheet->getRowDimension(1)->setRowHeight(32);
        $sheet->getRowDimension(2)->setRowHeight(28);
        $sheet->getRowDimension(3)->setRowHeight(32);
        $sheet->getRowDimension(4)->setRowHeight(26);
    }

    protected function buildMetalDetector($sheet, int $row): int
    {
        $groupRow = $row;
        $subRow = $row + 1;

        $groups = [
            'A' => ['Nama Produk', 'A', 'A'],
            'B' => ['No. Program', 'B', 'B'],
            'C' => ['Fe', 'C', 'E'],
            'F' => ['Non-Fe', 'F', 'H'],
            'I' => ['SUS 316', 'I', 'K'],
            'L' => ['In', 'L', 'L'],
            'M' => ['Out', 'M', 'M'],
            'N' => ['Keterangan', 'N', 'N'],
        ];

        foreach ($groups as $data) {
            [$label, $start, $end] = $data;

            if ($start === $end) {
                $sheet->mergeCells("{$start}{$groupRow}:{$start}{$subRow}");
            } else {
                $sheet->mergeCells("{$start}{$groupRow}:{$end}{$groupRow}");
            }

            $sheet->setCellValue("{$start}{$groupRow}", $label);
        }

        $subLabels = [
            'C' => 'Depan',
            'D' => 'Tengah',
            'E' => 'Belakang',
            'F' => 'Depan',
            'G' => 'Tengah',
            'H' => 'Belakang',
            'I' => 'Depan',
            'J' => 'Tengah',
            'K' => 'Belakang',
        ];

        foreach ($subLabels as $col => $label) {
            $sheet->setCellValue("{$col}{$subRow}", $label);
        }

        $this->styleRow($sheet, $groupRow, 12, true);
        $this->styleRow($sheet, $subRow, 12, true);

        $sheet->getRowDimension($groupRow)->setRowHeight(24);
        $sheet->getRowDimension($subRow)->setRowHeight(22);

        $row = $subRow + 1;

        $metalDetectors = $this->productionBatch->metalDetectors;

        if ($metalDetectors->isEmpty()) {
            $this->emptyBlockRow($sheet, $row, 'A', 'N');

            return $row + 2;
        }

        $statusCols = ['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];
        $statusFields = [
            'fe_depan', 'fe_tengah', 'fe_belakang',
            'non_fe_depan', 'non_fe_tengah', 'non_fe_belakang',
            'sus_316_depan', 'sus_316_tengah', 'sus_316_belakang',
        ];

        foreach ($metalDetectors as $item) {
            $sheet->setCellValue("A{$row}", $item->material->material_description ?? '-');
            $sheet->setCellValue("B{$row}", $item->no_program ?? '-');

            foreach ($statusCols as $i => $col) {
                $value = $item->{$statusFields[$i]};

                $sheet->setCellValue("{$col}{$row}", $value ?? '-');

                $this->styleStatusCell($sheet, "{$col}{$row}", $value);
            }

            $sheet->setCellValue("L{$row}", $item->in ?? '-');
            $sheet->setCellValue("M{$row}", $item->out ?? '-');
            $sheet->setCellValue("N{$row}", $item->keterangan ?? '-');

            $this->styleRow($sheet, $row, 12, false);

            $sheet->getRowDimension($row)->setRowHeight(40);

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

    protected function buildLegend($sheet, int $row): void
    {
        $sheet->mergeCells("A{$row}:N{$row}");

        $sheet->setCellValue(
            "A{$row}",
            'Keterangan: Hijau = Terdeteksi, Merah = Tidak terdeteksi.'
        );

        $sheet->getStyle("A{$row}")
            ->getFont()
            ->setItalic(true)
            ->setSize(12);

        $sheet->getRowDimension($row)->setRowHeight(20);
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
}