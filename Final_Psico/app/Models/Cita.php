<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    public $timestamps = false;
    
    use HasFactory;

    protected $fillable = [
        'especialidad', 'edad', 'genero', 'ubicacion', 'motivo',
        'exp_prev', 'dur_cita', 'fecha', 'eventos_locales',
        'condiciones_climaticas', 'promociones', 'num_medicos',
        'tiempo_espera', 'demografia'
    ];
}

