<?php
// app/Models/Log.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'log';

    protected $fillable = [
        'user_id',
        'entity',
        'date',
        'description',
    ];

    /** N Logs → 1 User */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
