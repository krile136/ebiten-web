<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_id
 * @property int $score
 */
class Score extends Model
{
    protected $fillable = [
        'user_id',
        'score',
    ];
}
