<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetalDetector extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_batch_id',
        'material_id',
        'no_program',
        'fe_depan',
        'fe_tengah',
        'fe_belakang',
        'non_fe_depan',
        'non_fe_tengah',
        'non_fe_belakang',
        'sus_316_depan',
        'sus_316_tengah',
        'sus_316_belakang',
        'in',
        'out',
        'keterangan',
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
