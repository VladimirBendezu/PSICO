<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalityTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'ap_1', 'ap_2', 'ap_3', 'ap_4', 'ap_5', 
        're_1', 're_2', 're_3', 're_4', 're_5',
        'ex_1', 'ex_2', 'ex_3', 'ex_4', 'ex_5',
        'am_1', 'am_2', 'am_3', 'am_4', 'am_5',
        'ne_1', 'ne_2', 'ne_3', 'ne_4', 'ne_5',

    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
