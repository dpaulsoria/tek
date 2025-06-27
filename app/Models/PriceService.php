<?php
// app/Models/PriceService.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceService extends Model
{
    use HasFactory;

    protected $table = 'price_service';

    protected $fillable = [
        'service_id',
        'value',
        'status',
    ];

    /** 1 PriceService → 1 Service */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
