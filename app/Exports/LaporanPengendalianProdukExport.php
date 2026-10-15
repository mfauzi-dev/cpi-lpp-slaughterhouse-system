<?php

namespace App\Exports;

use App\Models\ProductionBatch;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanPengendalianProdukExport implements WithMultipleSheets
{
    protected int $productionBatchId;

    protected ?ProductionBatch $productionBatch = null;

    public function __construct(int $productionBatchId)
    {
        $this->productionBatchId = $productionBatchId;

        $this->productionBatch = ProductionBatch::with([
            'bahanBakus.material',
            'dataHasilProduksis.material',
            'rekapitulasiProduksis',
            'productivities.material',
            'metalDetectors.material',
        ])->findOrFail($productionBatchId);
    }

    public function sheets(): array
    {
        return [
            new LaporanPengendalianProdukDepanSheet($this->productionBatch),
            new LaporanPengendalianProdukBelakangSheet($this->productionBatch),
        ];
    }
}