<?php
// app/Models/Person.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'person';

    protected $fillable = [
        'document',
        'first_name',
        'last_name',
        'address',
        'phone',
        'email',
    ];

    /** 1 Person → N Cites */
    public function cites()
    {
        return $this->hasMany(Cite::class, 'cliente_id');
    }

    /** 1 Person → N Attentions (vía citas) */
    public function attentions()
    {
        return $this->hasManyThrough(
            Attention::class,  // Modelo destino
            Cite::class,       // Pivot (intermedio)
            'cliente_id',      // FK en cites -> person
            'cite_id',         // FK en attention -> cite
            'id',              // PK en person
            'id'               // PK en cite
        );
    }
}
