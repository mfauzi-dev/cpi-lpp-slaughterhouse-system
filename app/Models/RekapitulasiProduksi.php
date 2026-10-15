<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapitulasiProduksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_batch_id',
        'mesin',
        'waktu_start',
        'waktu_stop',
        'operation_time',
        'speed_kapasitas',
        'aktual',
        'persen_pencapaian',
        'output_kg',
        'downtime',
        'penyebab_downtime',
        'tindakan_perbaikan',
        'pic_operator',
    ];

    protected $casts = [
        'operation_time' => 'decimal:2',
        'speed_kapasitas' => 'decimal:2',
        'aktual' => 'decimal:2',
        'persen_pencapaian' => 'decimal:2',
        'output_kg' => 'decimal:2',
        'downtime' => 'decimal:2',
    ];

    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }
}
