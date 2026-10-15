<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataHasilProduksi extends Model
{
    use HasFactory;

        protected $fillable = [
        'production_batch_id',
        'material_id',
        'domestik_kg',
        'further_kg',
        'frozen_kg',
        'rm',
        'total_bag',
        'total_kg',
        'loss_kg',
        'loss_persen',
        'pickup_kg',
        'pickup_persen',
        'pengecekan_suhu_jam',
        'pengecekan_suhu_suhu',
        'packaging_nama_kemasan',
        'packaging_jumlah_kg',
    ];

    protected $casts = [
        'domestik_kg' => 'decimal:2',
        'further_kg' => 'decimal:2',
        'frozen_kg' => 'decimal:2',
        'rm' => 'decimal:2',
        'total_bag' => 'integer',
        'total_kg' => 'decimal:2',
        'loss_kg' => 'decimal:2',
        'loss_persen' => 'decimal:2',
        'pickup_kg' => 'decimal:2',
        'pickup_persen' => 'decimal:2',
        'pengecekan_suhu_suhu' => 'decimal:2',
        'packaging_jumlah_kg' => 'decimal:2',
    ];

    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
