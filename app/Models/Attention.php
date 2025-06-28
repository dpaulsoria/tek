<?php
// app/Models/Attention.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attention extends Model
{
    use HasFactory;

    protected $table = 'attention';
    protected $casts = [
        'date'          => 'date',
        'price_service' => 'decimal:2',
    ];

    protected $fillable = [
        'date',
        'cite_id',
        'service_id',
        'price_service',
    ];

    /** N Attention → 1 Cite */
    public function cite()
    {
        return $this->belongsTo(Cite::class, 'cite_id');
    }

    /** N Attention → 1 Service */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
