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
}
