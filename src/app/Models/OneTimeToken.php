<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneTimeToken extends Model
{
    use HasFactory;

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'token',
        'is_used',
    ];
}
