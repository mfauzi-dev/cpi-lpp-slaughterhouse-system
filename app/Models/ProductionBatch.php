<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'shift',
        'conveyor',
        'absen_worker',
        'tanggal_transfer',
        'tanggal_proses',
        'cost_center_name',
        'no_po',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_transfer' => 'date',
        'tanggal_proses' => 'date',
        'shift' => 'integer',
        'absen_worker' => 'integer',
    ];

    public function bahanBakus()
    {
        return $this->hasMany(BahanBaku::class, 'production_batch_id');
    }

    public function dataHasilProduksis()
    {
        return $this->hasMany(DataHasilProduksi::class);
    }

    public function rekapitulasiProduksis()
    {
        return $this->hasMany(RekapitulasiProduksi::class);
    }

    public function productivities()
    {
        return $this->hasMany(Productivity::class);
    }

    

    public function metalDetectors()
    {
        return $this->hasMany(MetalDetector::class);
    }
}