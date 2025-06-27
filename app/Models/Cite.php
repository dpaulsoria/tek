<?php
// app/Models/Cite.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cite extends Model
{
    use HasFactory;

    protected $table = 'cite';

    protected $fillable = [
        'date',
        'cliente_id',
        'amount_attention',
        'time_arrival',
        'total_service',
        'status',
    ];

    /** N Cites → 1 Person */
    public function person()
    {
        return $this->belongsTo(Person::class, 'cliente_id');
    }

    /** 1 Cite → N Attention */
    public function attentions()
    {
        return $this->hasMany(Attention::class, 'cite_id');
    }
}
