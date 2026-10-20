<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_batch_id',
        'cost_center_name',
        'no_po',
        'material_id',
        'jumlah_bag',
        'jumlah_kg',
    ];

    protected $casts = [
        'jumlah_bag' => 'integer',
        'jumlah_kg' => 'decimal:2',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
