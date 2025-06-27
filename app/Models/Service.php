<?php
// app/Models/Service.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'service';

    protected $fillable = [
        'name',
        'slug',
    ];

    /** 1 Service → 1 PriceService */
    public function priceService()
    {
        return $this->hasOne(PriceService::class, 'service_id');
    }

    /** 1 Service → N Attention */
    public function attentions()
    {
        return $this->hasMany(Attention::class, 'service_id');
    }
}
