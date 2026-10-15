<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productivity extends Model
{
    use HasFactory;

     protected $fillable = [
        'production_batch_id',
        'material_id',
        'worker_indirect',
        'worker_direct',
        'worker_total',
        'total_weight',
        'total_time',
        'productivity_indirect',
        'productivity_direct',
    ];

    protected $casts = [
        'worker_indirect' => 'integer',
        'worker_direct' => 'integer',
        'worker_total' => 'integer',
        'total_weight' => 'decimal:2',
        'total_time' => 'decimal:2',
        'productivity_indirect' => 'decimal:2',
        'productivity_direct' => 'decimal:2',
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
